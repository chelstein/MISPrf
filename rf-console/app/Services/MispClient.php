<?php

namespace App\Services;

use App\Services\Exceptions\MispClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * Thin client over the MISP REST API.
 *
 * MISP owns the schema. This client never mutates MISP core; it only
 * reads (and, when wired, writes) through documented MISP endpoints.
 *
 * Every method returns MISP-shape data (the same envelopes you would
 * get from a live server). When RF_CONSOLE_USE_MOCK=true, the client
 * delegates to MockData and re-shapes the rows into MISP-like
 * envelopes so callers and tests get a consistent contract regardless
 * of mode.
 */
class MispClient
{
    private ?Client $http = null;

    public function __construct(
        private string $baseUrl,
        private string $apiKey,
        private bool $verifyTls = true,
        private bool $useMock = true,
        private ?MockData $mockData = null,
    ) {
        $this->mockData ??= new MockData();
    }

    public function isMock(): bool
    {
        return $this->useMock;
    }

    /**
     * POST /events/restSearch
     *
     * @return array<int, array{Event: array<string, mixed>}>
     */
    public function searchEvents(array $params = []): array
    {
        if ($this->useMock) {
            return $this->mockEventList($params);
        }
        $payload = $this->post('events/restSearch', $params);
        $list = $payload['response'] ?? $payload;
        return is_array($list) ? array_values($list) : [];
    }

    /**
     * GET /events/view/{id}
     *
     * @return array<string, mixed>|null Inner Event object, or null if absent.
     */
    public function getEvent(string $id): ?array
    {
        if ($this->useMock) {
            foreach ($this->mockEventList([]) as $env) {
                if (($env['Event']['id'] ?? null) === $id) {
                    return $env['Event'];
                }
            }
            return null;
        }
        $payload = $this->get('events/view/'.rawurlencode($id));
        return $payload['Event'] ?? null;
    }

    /**
     * POST /attributes/restSearch
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchAttributes(array $params = []): array
    {
        if ($this->useMock) {
            return $this->mockAttributeList($params);
        }
        $payload = $this->post('attributes/restSearch', $params);
        $list = $payload['response']['Attribute']
            ?? $payload['Attribute']
            ?? $payload['response']
            ?? [];
        return is_array($list) ? array_values($list) : [];
    }

    /**
     * POST /sightings/restSearch
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchSightings(array $params = []): array
    {
        if ($this->useMock) {
            return $this->mockSightingList($params);
        }
        $payload = $this->post('sightings/restSearch', $params);
        $list = $payload['response']
            ?? $payload['Sighting']
            ?? [];
        return is_array($list) ? array_values($list) : [];
    }

    /**
     * GET /tags
     *
     * @return array<int, array<string, mixed>>
     */
    public function listTags(): array
    {
        if ($this->useMock) {
            return $this->mockTagList();
        }
        $payload = $this->get('tags');
        $list = $payload['Tag'] ?? $payload;
        return is_array($list) ? array_values($list) : [];
    }

    /**
     * GET /galaxies
     *
     * @return array<int, array<string, mixed>>
     */
    public function listGalaxies(): array
    {
        if ($this->useMock) {
            return $this->mockGalaxyList();
        }
        $payload = $this->get('galaxies');
        if (is_array($payload) && isset($payload[0]['Galaxy'])) {
            return array_values(array_map(fn ($g) => $g['Galaxy'], $payload));
        }
        return is_array($payload) ? array_values($payload) : [];
    }

    /** GET /servers/getVersion */
    public function getServerVersion(): array
    {
        if ($this->useMock) {
            return [
                'version' => 'mock-2.4.190',
                'perm_sync' => true,
                'mock' => true,
            ];
        }
        return $this->get('servers/getVersion');
    }

    /** Lightweight reachability probe. Never throws. */
    public function ping(): bool
    {
        try {
            return ! empty($this->getServerVersion());
        } catch (\Throwable) {
            return false;
        }
    }

    // ------------------------------------------------------------------
    // HTTP plumbing
    // ------------------------------------------------------------------

    private function get(string $path): array
    {
        return $this->request('GET', $path);
    }

    private function post(string $path, array $body): array
    {
        return $this->request('POST', $path, $body);
    }

    private function request(string $method, string $path, ?array $body = null): array
    {
        if ($this->baseUrl === '' || $this->apiKey === '') {
            throw new MispClientException(
                'MISP base URL or API key is not configured. Set MISP_BASE_URL and MISP_API_KEY, '
                .'or set RF_CONSOLE_USE_MOCK=true.'
            );
        }

        $opts = [];
        if ($body !== null) {
            $opts['json'] = $body;
        }

        try {
            $resp = $this->http()->request($method, $path, $opts);
        } catch (GuzzleException $e) {
            Log::warning('[MispClient] transport error', [
                'method' => $method, 'path' => $path, 'err' => $e->getMessage(),
            ]);
            throw new MispClientException('Transport error talking to MISP: '.$e->getMessage(), 0, $e);
        }

        $status = $resp->getStatusCode();
        $raw = (string) $resp->getBody();

        if ($status < 200 || $status >= 300) {
            Log::warning('[MispClient] non-2xx', [
                'method' => $method, 'path' => $path, 'status' => $status,
                'body' => substr($raw, 0, 500),
            ]);
            throw new MispClientException('MISP returned HTTP '.$status.' for '.$path);
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded)) {
            throw new MispClientException('MISP returned non-JSON body for '.$path);
        }

        // MISP error envelopes occasionally come back with a 200 status
        // when an action is rejected at the application layer. Detect
        // and surface them so callers do not silently swallow errors.
        if (isset($decoded['name'], $decoded['message'], $decoded['url'])
            && ! isset($decoded['response'], $decoded['Event'], $decoded['Attribute'], $decoded['Tag'])) {
            throw new MispClientException(
                'MISP error: '.$decoded['name'].' - '.$decoded['message'].' (url: '.$decoded['url'].')'
            );
        }

        return $decoded;
    }

    private function http(): Client
    {
        return $this->http ??= new Client([
            'base_uri' => rtrim($this->baseUrl, '/').'/',
            'verify' => $this->verifyTls,
            'headers' => [
                'Authorization' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'rf-console/0.1 (+MispClient)',
            ],
            'http_errors' => false,
            'timeout' => 15,
            'connect_timeout' => 5,
        ]);
    }

    // ------------------------------------------------------------------
    // Mock shape converters - keep the live and mock contracts identical.
    // ------------------------------------------------------------------

    private function mockEventList(array $params): array
    {
        $events = $this->mockData->events();

        if (! empty($params['eventid'])) {
            $needle = (string) $params['eventid'];
            $events = array_values(array_filter($events, fn ($e) => $e['id'] === $needle));
        }
        if (! empty($params['tags']) && is_array($params['tags'])) {
            $needles = array_map('strval', $params['tags']);
            $events = array_values(array_filter(
                $events,
                fn ($e) => count(array_intersect($needles, $e['tags'] ?? [])) > 0
            ));
        }
        if (! empty($params['limit'])) {
            $events = array_slice($events, 0, (int) $params['limit']);
        }

        return array_map(fn ($e) => ['Event' => $this->shapeMockEvent($e)], $events);
    }

    private function shapeMockEvent(array $e): array
    {
        $threat = match ($e['severity'] ?? 'info') {
            'critical', 'high' => '1',
            'medium' => '2',
            'low' => '3',
            default => '4',
        };
        $dist = match ($e['sharing'] ?? '') {
            'Org-only' => '0',
            'Community' => '1',
            'Connected' => '2',
            'All' => '3',
            default => '1',
        };
        $ts = strtotime($e['updated'] ?? 'now') ?: time();

        return [
            'id' => $e['id'],
            'info' => $e['info'],
            'threat_level_id' => $threat,
            'distribution' => $dist,
            'date' => date('Y-m-d', $ts),
            'timestamp' => (string) $ts,
            'Orgc' => ['name' => $e['source'] ?? 'Unknown'],
            'Org' => ['name' => $e['source'] ?? 'Unknown'],
            'Tag' => array_map(fn ($t) => ['name' => $t], $e['tags'] ?? []),
        ];
    }

    private function mockAttributeList(array $params): array
    {
        $rows = $this->mockData->allAttributes();
        if (! empty($params['eventid'])) {
            $needle = (string) $params['eventid'];
            $rows = array_values(array_filter($rows, fn ($r) => $r['event_id'] === $needle));
        }
        if (! empty($params['type'])) {
            $needle = (string) $params['type'];
            $rows = array_values(array_filter($rows, fn ($r) => $r['type'] === $needle));
        }
        if (! empty($params['limit'])) {
            $rows = array_slice($rows, 0, (int) $params['limit']);
        }

        return array_map(fn ($a) => [
            'event_id' => $a['event_id'],
            'type' => $a['type'],
            'category' => $a['category'],
            'value' => $a['value'],
            'to_ids' => $a['ids'] ? '1' : '0',
            'comment' => $a['comment'],
            'distribution' => '5',
        ], $rows);
    }

    private function mockSightingList(array $params): array
    {
        $eventId = $params['context_id'] ?? $params['event_id'] ?? null;
        $rows = $eventId
            ? $this->mockData->sightingsForEvent((string) $eventId)
            : $this->mockData->recentSightings();

        $i = 0;
        return array_map(function ($s) use (&$i, $eventId) {
            $i++;
            return [
                'id' => (string) $i,
                'event_id' => $eventId !== null ? (string) $eventId : ($s['event'] ?? null),
                'date_sighting' => (string) (strtotime($s['ts'] ?? 'now') ?: time()),
                'source' => $s['source'] ?? '',
                'type' => '0',
            ];
        }, $rows);
    }

    private function mockTagList(): array
    {
        $tags = [];
        foreach ($this->mockData->events() as $e) {
            foreach (($e['tags'] ?? []) as $t) {
                $tags[$t] = true;
            }
        }
        $i = 0;
        $out = [];
        foreach (array_keys($tags) as $name) {
            $i++;
            $out[] = [
                'id' => (string) $i,
                'name' => $name,
                'colour' => '#1A325F',
                'exportable' => true,
            ];
        }
        return $out;
    }

    private function mockGalaxyList(): array
    {
        return [
            ['id' => '1', 'namespace' => 'rf', 'name' => 'RF Threat Actors', 'type' => 'rf-threat-actor', 'description' => 'Mock galaxy: unauthorised emitters'],
            ['id' => '2', 'namespace' => 'rf', 'name' => 'RF Modulation Family', 'type' => 'rf-modulation', 'description' => 'Mock galaxy: modulation classes'],
            ['id' => '3', 'namespace' => 'rf', 'name' => 'RF Regulatory Regimes', 'type' => 'rf-regulatory', 'description' => 'Mock galaxy: FCC, ITU, OFCOM'],
        ];
    }
}
