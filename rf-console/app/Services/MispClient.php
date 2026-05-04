<?php

namespace App\Services;

use GuzzleHttp\Client;
use RuntimeException;

/**
 * Thin client over the MISP REST API.
 *
 * MISP owns the schema. This client never mutates MISP core; it only
 * reads (and, when wired, writes) through documented MISP endpoints.
 *
 * All methods are stubs returning empty arrays until live integration is
 * enabled via RF_CONSOLE_USE_MOCK=false. Mock data lives in MockData.
 */
class MispClient
{
    private ?Client $http = null;

    public function __construct(
        private string $baseUrl,
        private string $apiKey,
        private bool $verifyTls = true,
        private bool $useMock = true,
    ) {
    }

    public function isMock(): bool
    {
        return $this->useMock;
    }

    public function searchEvents(array $params = []): array
    {
        return $this->stub('events.restSearch', $params);
    }

    public function getEvent(string $id): array
    {
        return $this->stub('events.view/'.$id);
    }

    public function searchAttributes(array $params = []): array
    {
        return $this->stub('attributes.restSearch', $params);
    }

    public function searchSightings(array $params = []): array
    {
        return $this->stub('sightings.restSearch', $params);
    }

    public function listTags(): array
    {
        return $this->stub('tags.index');
    }

    public function listGalaxies(): array
    {
        return $this->stub('galaxies.index');
    }

    public function getServerVersion(): array
    {
        return $this->stub('servers.getVersion');
    }

    private function stub(string $endpoint, array $params = []): array
    {
        if ($this->useMock) {
            return [];
        }

        throw new RuntimeException(
            'MispClient::'.$endpoint.' not implemented yet - '
            .'set RF_CONSOLE_USE_MOCK=true or implement the call.'
        );
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
            ],
            'http_errors' => false,
            'timeout' => 15,
        ]);
    }
}
