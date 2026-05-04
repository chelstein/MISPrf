<?php

namespace App\Services;

/**
 * Pure converter from MISP-shape envelopes to the UI-shape arrays the
 * Blade views consume. No I/O, no DI - cheap to construct, easy to
 * unit-test in isolation.
 */
class MispTransformer
{
    private const SEVERITY_FROM_THREAT = [
        '1' => 'high',
        '2' => 'medium',
        '3' => 'low',
        '4' => 'info',
    ];

    private const SHARING_FROM_DIST = [
        '0' => 'Org-only',
        '1' => 'Community',
        '2' => 'Connected',
        '3' => 'All',
        '4' => 'Sharing group',
        '5' => 'Inherit',
    ];

    public function event(array $envelope): array
    {
        $e = $envelope['Event'] ?? $envelope;

        $tags = array_values(array_filter(array_map(
            fn ($t) => is_array($t) ? ($t['name'] ?? null) : null,
            $e['Tag'] ?? []
        )));

        $severity = self::SEVERITY_FROM_THREAT[(string) ($e['threat_level_id'] ?? '4')] ?? 'info';
        $sharing = self::SHARING_FROM_DIST[(string) ($e['distribution'] ?? '1')] ?? 'Community';
        $status = $this->statusFromTags($tags);
        $freq = $this->frequencyFromAttributes($e['Attribute'] ?? []);
        $band = $this->bandFromTags($tags) ?? $this->bandFromFreq($freq) ?? '-';
        $source = $e['Orgc']['name'] ?? $e['Org']['name'] ?? 'Unknown';
        $ts = (int) ($e['timestamp'] ?? $e['publish_timestamp'] ?? time());

        return [
            'id' => $this->prettyId($e['id'] ?? ''),
            'misp_id' => (string) ($e['id'] ?? ''),
            'info' => (string) ($e['info'] ?? ''),
            'severity' => $severity,
            'status' => $status,
            'frequency' => $freq ?? '-',
            'band' => $band,
            'source' => $source,
            'tags' => $tags,
            'sharing' => $sharing,
            'updated' => date('Y-m-d H:i', $ts).'Z',
            'confidence' => $this->confidenceFromTags($tags),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function events(array $envelopes): array
    {
        return array_map([$this, 'event'], $envelopes);
    }

    public function attribute(array $a, ?string $eventId = null, ?string $eventInfo = null): array
    {
        $eid = $eventId ?? (string) ($a['event_id'] ?? '');

        return [
            'event_id' => $this->prettyId($eid),
            'event_info' => $eventInfo,
            'type' => (string) ($a['type'] ?? ''),
            'category' => (string) ($a['category'] ?? ''),
            'value' => (string) ($a['value'] ?? ''),
            'ids' => $this->boolish($a['to_ids'] ?? false),
            'comment' => (string) ($a['comment'] ?? ''),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function attributes(array $list, ?string $eventId = null, ?string $eventInfo = null): array
    {
        return array_map(fn ($a) => $this->attribute($a, $eventId, $eventInfo), $list);
    }

    public function sighting(array $s): array
    {
        $ts = (int) ($s['date_sighting'] ?? time());

        return [
            'ts' => date('H:i', $ts).'Z',
            'event' => $this->prettyId((string) ($s['event_id'] ?? '')),
            'source' => (string) ($s['source'] ?? ($s['org_id'] ?? '?')),
            'detail' => (string) ($s['source'] ?? ($s['value'] ?? '-')),
            'lat' => $s['lat'] ?? null,
            'lon' => $s['lon'] ?? null,
            'snr' => $s['snr'] ?? null,
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function sightings(array $list): array
    {
        return array_map([$this, 'sighting'], $list);
    }

    /** Best-effort timeline derived from event timestamps + tags. */
    public function timelineFor(array $envelope): array
    {
        $e = $envelope['Event'] ?? $envelope;
        $items = [];
        $created = (int) ($e['timestamp'] ?? time());

        $items[] = [
            'ts' => date('H:i', $created).'Z',
            'kind' => 'created',
            'text' => 'Event '.$this->prettyId((string) ($e['id'] ?? '')).' created',
        ];

        $tagNames = array_filter(array_map(
            fn ($t) => is_array($t) ? ($t['name'] ?? null) : null,
            $e['Tag'] ?? []
        ));
        if (! empty($tagNames)) {
            $items[] = [
                'ts' => date('H:i', $created).'Z',
                'kind' => 'classification',
                'text' => 'Tagged: '.implode(', ', $tagNames),
            ];
        }

        $pub = (int) ($e['publish_timestamp'] ?? 0);
        if ($pub > 0) {
            $items[] = [
                'ts' => date('H:i', $pub).'Z',
                'kind' => 'sighting',
                'text' => 'Published',
            ];
        }

        return $items;
    }

    public function prettyId(string|int $raw): string
    {
        $raw = (string) $raw;
        if ($raw === '') {
            return '';
        }
        if (str_starts_with($raw, 'RF-')) {
            return $raw;
        }
        if (preg_match('/^\d+$/', $raw)) {
            return 'RF-'.$raw;
        }
        return $raw;
    }

    /** Strip the UI prefix to recover a raw MISP id. */
    public function rawId(string $id): string
    {
        return preg_replace('/^RF-/i', '', $id) ?? $id;
    }

    private function statusFromTags(array $tags): string
    {
        $lower = array_map('strtolower', $tags);
        foreach (['anomaly', 'jamming', 'spoof', 'interference', 'pirate', 'unlicensed'] as $needle) {
            if ($this->tagMatches($lower, $needle)) {
                return 'anomaly';
            }
        }
        foreach (['licensed', 'verified', 'authoritative'] as $needle) {
            if ($this->tagMatches($lower, $needle)) {
                return 'verified';
            }
        }
        if ($this->tagMatches($lower, 'investigating')) {
            return 'investigating';
        }
        return 'observed';
    }

    private function tagMatches(array $lower, string $needle): bool
    {
        foreach ($lower as $t) {
            if (str_contains($t, $needle)) {
                return true;
            }
        }
        return false;
    }

    private function frequencyFromAttributes(array $attrs): ?string
    {
        foreach ($attrs as $a) {
            $type = $a['type'] ?? '';
            if (in_array($type, ['frequency-hz', 'frequency'], true)) {
                $v = (float) ($a['value'] ?? 0);
                if ($v >= 1e9) {
                    return number_format($v / 1e9, 3).' GHz';
                }
                if ($v >= 1e6) {
                    return number_format($v / 1e6, 3).' MHz';
                }
                if ($v >= 1e3) {
                    return number_format($v / 1e3, 3).' kHz';
                }
                return number_format($v, 2).' Hz';
            }
        }
        return null;
    }

    private function bandFromTags(array $tags): ?string
    {
        $map = [
            'marine' => 'VHF Marine',
            'aviation' => 'Aviation',
            'ais' => 'AIS',
            'gnss' => 'GNSS L1',
            'fm' => 'FM Broadcast',
            'stl' => 'STL',
            'hf' => 'HF',
            'vhf' => 'VHF',
            'uhf' => 'UHF',
            'elf' => 'ELF',
        ];
        foreach ($tags as $t) {
            $lt = strtolower($t);
            foreach ($map as $needle => $label) {
                if (str_contains($lt, $needle)) {
                    return $label;
                }
            }
        }
        return null;
    }

    private function bandFromFreq(?string $freq): ?string
    {
        if (! $freq || ! preg_match('/^([\d.]+)\s*(GHz|MHz|kHz|Hz)/i', $freq, $m)) {
            return null;
        }
        $v = (float) $m[1];
        $unit = strtolower($m[2]);
        $hz = match ($unit) {
            'ghz' => $v * 1e9,
            'mhz' => $v * 1e6,
            'khz' => $v * 1e3,
            default => $v,
        };
        return match (true) {
            $hz <= 30 => 'ELF',
            $hz < 30e6 => 'HF',
            $hz < 88e6 => 'VHF Low',
            $hz < 108e6 => 'FM Broadcast',
            $hz < 137e6 => 'Aviation',
            $hz < 174e6 => 'VHF High',
            $hz < 1e9 => 'UHF',
            default => 'L-band+',
        };
    }

    private function confidenceFromTags(array $tags): float
    {
        $score = 0.7;
        foreach ($tags as $t) {
            $lt = strtolower($t);
            if (str_contains($lt, 'verified') || str_contains($lt, 'licensed')) {
                $score = max($score, 0.95);
            }
            if (str_contains($lt, 'unconfirmed') || str_contains($lt, 'spoof')) {
                $score = min($score, 0.55);
            }
            if (str_contains($lt, 'critical') || str_contains($lt, 'jamming')) {
                $score = max($score, 0.8);
            }
        }
        return $score;
    }

    private function boolish(mixed $v): bool
    {
        if (is_bool($v)) {
            return $v;
        }
        if (is_int($v)) {
            return $v === 1;
        }
        if (is_string($v)) {
            return in_array(strtolower($v), ['1', 'true', 'yes', 'on'], true);
        }
        return false;
    }
}
