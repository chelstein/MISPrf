<?php

namespace App\Services;

/**
 * Static seed data for the rf-console UI. Replaced by MispClient calls
 * once RF_CONSOLE_USE_MOCK=false. Fields mirror MISP terminology with
 * UI-side labels applied at render time.
 */
class MockData
{
    public function dashboardMetrics(): array
    {
        return [
            ['label' => 'Active RF Events', 'value' => '47', 'delta' => '+6 / 24h', 'tone' => 'signal', 'icon' => 'pulse'],
            ['label' => 'Verified Licensed', 'value' => '312', 'delta' => '+12 / 7d', 'tone' => 'gold', 'icon' => 'badge'],
            ['label' => 'Anomalies Open', 'value' => '8', 'delta' => '+2 / 24h', 'tone' => 'anomaly', 'icon' => 'alert'],
            ['label' => 'Sources Online', 'value' => '23 / 25', 'delta' => '92%', 'tone' => 'slate', 'icon' => 'antenna'],
        ];
    }

    public function events(): array
    {
        return [
            ['id' => 'RF-2487', 'info' => 'Unauthorised carrier on Marine VHF Ch 16', 'severity' => 'high', 'status' => 'observed', 'frequency' => '156.800 MHz', 'band' => 'VHF Marine', 'source' => 'Buoy-IQ Tomsk', 'tags' => ['unlicensed', 'marine', 'interference'], 'sharing' => 'Community', 'updated' => '2026-05-04 14:12Z', 'confidence' => 0.86],
            ['id' => 'RF-2486', 'info' => 'Schumann harmonic spike (7.83 Hz)', 'severity' => 'low', 'status' => 'observed', 'frequency' => '7.83 Hz', 'band' => 'ELF', 'source' => 'Schumann Array', 'tags' => ['natural', 'background'], 'sharing' => 'All', 'updated' => '2026-05-04 13:55Z', 'confidence' => 0.92],
            ['id' => 'RF-2485', 'info' => 'Pirate FM broadcast 92.1 MHz', 'severity' => 'high', 'status' => 'anomaly', 'frequency' => '92.100 MHz', 'band' => 'FM Broadcast', 'source' => 'SDR-East', 'tags' => ['pirate', 'broadcast', 'unlicensed'], 'sharing' => 'Community', 'updated' => '2026-05-04 13:40Z', 'confidence' => 0.78],
            ['id' => 'RF-2484', 'info' => 'Licensed amateur contest 14.250 MHz', 'severity' => 'info', 'status' => 'verified', 'frequency' => '14.250 MHz', 'band' => 'HF 20m', 'source' => 'WebSDR-NL', 'tags' => ['amateur', 'licensed', 'contest'], 'sharing' => 'All', 'updated' => '2026-05-04 13:11Z', 'confidence' => 0.97],
            ['id' => 'RF-2483', 'info' => 'GPS L1 jamming pattern (intermittent)', 'severity' => 'critical', 'status' => 'anomaly', 'frequency' => '1575.42 MHz', 'band' => 'GNSS L1', 'source' => 'Sentinel-Sat-3', 'tags' => ['jamming', 'gnss', 'critical-infra'], 'sharing' => 'Org-only', 'updated' => '2026-05-04 12:48Z', 'confidence' => 0.81],
            ['id' => 'RF-2482', 'info' => 'ADS-B uplink anomaly KORD sector', 'severity' => 'medium', 'status' => 'investigating', 'frequency' => '1090 MHz', 'band' => 'Aviation', 'source' => 'PiAware-ORD', 'tags' => ['aviation', 'ads-b'], 'sharing' => 'Community', 'updated' => '2026-05-04 12:30Z', 'confidence' => 0.74],
            ['id' => 'RF-2481', 'info' => 'Maritime AIS spoof candidate (MMSI duplicate)', 'severity' => 'high', 'status' => 'investigating', 'frequency' => '161.975 MHz', 'band' => 'AIS', 'source' => 'AIS-Coast-7', 'tags' => ['ais', 'spoof', 'maritime'], 'sharing' => 'Community', 'updated' => '2026-05-04 11:55Z', 'confidence' => 0.69],
            ['id' => 'RF-2480', 'info' => 'Licensed STL 950 MHz path verified', 'severity' => 'info', 'status' => 'verified', 'frequency' => '951.500 MHz', 'band' => 'STL', 'source' => 'FCC ULS', 'tags' => ['stl', 'licensed', 'broadcast'], 'sharing' => 'All', 'updated' => '2026-05-04 11:20Z', 'confidence' => 0.99],
        ];
    }

    public function attributesForEvent(string $eventId): array
    {
        return [
            ['type' => 'frequency-hz', 'category' => 'RF Signal', 'value' => '156800000', 'ids' => true, 'comment' => 'Center frequency'],
            ['type' => 'modulation', 'category' => 'RF Signal', 'value' => 'FM (NBFM)', 'ids' => false, 'comment' => 'Detected modulation'],
            ['type' => 'bandwidth-hz', 'category' => 'RF Signal', 'value' => '12500', 'ids' => false, 'comment' => '12.5 kHz channel'],
            ['type' => 'snr-db', 'category' => 'RF Signal', 'value' => '18.4', 'ids' => false, 'comment' => 'Estimated SNR'],
            ['type' => 'geo-bbox', 'category' => 'Location', 'value' => '56.40,84.85,56.55,85.05', 'ids' => false, 'comment' => 'Bearing fan, Tomsk'],
            ['type' => 'sigmf-recording', 'category' => 'Artifact', 'value' => 'evt-'.$eventId.'.sigmf-meta', 'ids' => false, 'comment' => 'Capture artifact'],
        ];
    }

    public function allAttributes(): array
    {
        $rows = [];
        foreach ($this->events() as $e) {
            foreach ($this->attributesForEvent($e['id']) as $a) {
                $rows[] = $a + ['event_id' => $e['id'], 'event_info' => $e['info']];
            }
        }
        return $rows;
    }

    public function sightingsForEvent(string $eventId): array
    {
        return [
            ['ts' => '2026-05-04 14:12:03Z', 'source' => 'Buoy-IQ Tomsk', 'lat' => 56.4847, 'lon' => 84.9476, 'snr' => 18.4],
            ['ts' => '2026-05-04 14:11:48Z', 'source' => 'Buoy-IQ Tomsk', 'lat' => 56.4848, 'lon' => 84.9472, 'snr' => 16.9],
            ['ts' => '2026-05-04 14:10:55Z', 'source' => 'WebSDR-NL', 'lat' => 52.0116, 'lon' => 4.3571, 'snr' => 7.1],
        ];
    }

    public function recentSightings(): array
    {
        return [
            ['ts' => '14:12Z', 'event' => 'RF-2487', 'source' => 'Buoy-IQ Tomsk', 'detail' => 'Carrier confirmed, 18.4 dB SNR'],
            ['ts' => '13:55Z', 'event' => 'RF-2486', 'source' => 'Schumann Array', 'detail' => 'Harmonic spike +3.2 dB'],
            ['ts' => '13:40Z', 'event' => 'RF-2485', 'source' => 'SDR-East', 'detail' => 'Voice content detected'],
            ['ts' => '12:48Z', 'event' => 'RF-2483', 'source' => 'Sentinel-Sat-3', 'detail' => 'Pulsed jamming, 200 ms duty'],
            ['ts' => '12:30Z', 'event' => 'RF-2482', 'source' => 'PiAware-ORD', 'detail' => 'Phantom track, sector 14'],
        ];
    }

    public function timelineForEvent(string $eventId): array
    {
        return [
            ['ts' => '14:12Z', 'kind' => 'sighting', 'text' => 'New sighting from Buoy-IQ Tomsk'],
            ['ts' => '14:08Z', 'kind' => 'classification', 'text' => 'Tagged: unlicensed, marine'],
            ['ts' => '14:02Z', 'kind' => 'correlation', 'text' => 'Correlated with RF-2461 (similar carrier)'],
            ['ts' => '13:58Z', 'kind' => 'created', 'text' => 'Event '.$eventId.' created from automated detection'],
        ];
    }

    public function spectrum(): array
    {
        $bins = [];
        $rng = mt_rand(0, 1);
        for ($i = 0; $i < 96; $i++) {
            $base = -95 + sin($i / 7.0 + $rng) * 4;
            $spike = ($i === 42 || $i === 43 || $i === 44) ? 28 : 0;
            $jitter = (mt_rand(0, 100) / 100) * 6;
            $bins[] = [
                'f' => 156.0 + $i * 0.025,
                'p' => round($base + $spike + $jitter, 1),
            ];
        }
        return $bins;
    }

    public function frequencyBands(): array
    {
        return [
            ['band' => 'ELF', 'range' => '3 - 30 Hz', 'events' => 4, 'verified' => 4, 'anomalies' => 0],
            ['band' => 'HF 20 m', 'range' => '14.000 - 14.350 MHz', 'events' => 22, 'verified' => 21, 'anomalies' => 1],
            ['band' => 'FM Broadcast', 'range' => '88 - 108 MHz', 'events' => 56, 'verified' => 49, 'anomalies' => 7],
            ['band' => 'VHF Marine', 'range' => '156 - 162 MHz', 'events' => 31, 'verified' => 25, 'anomalies' => 6],
            ['band' => 'AIS', 'range' => '161.975 / 162.025 MHz', 'events' => 14, 'verified' => 11, 'anomalies' => 3],
            ['band' => 'Aviation', 'range' => '108 - 137 MHz', 'events' => 19, 'verified' => 18, 'anomalies' => 1],
            ['band' => 'GNSS L1', 'range' => '1575.42 MHz', 'events' => 5, 'verified' => 1, 'anomalies' => 4],
            ['band' => 'STL', 'range' => '944 - 952 MHz', 'events' => 9, 'verified' => 9, 'anomalies' => 0],
        ];
    }

    public function allocations(): array
    {
        return [
            ['callsign' => 'WXYZ-FM', 'freq' => '92.100 MHz', 'holder' => 'Big Sky Media LLC', 'expires' => '2027-09-30', 'state' => 'verified'],
            ['callsign' => 'KAA-7711', 'freq' => '951.500 MHz', 'holder' => 'Northstar Broadcasting', 'expires' => '2028-04-12', 'state' => 'verified'],
            ['callsign' => '-', 'freq' => '156.800 MHz', 'holder' => 'Marine Distress (Ch 16)', 'expires' => 'standing', 'state' => 'verified'],
            ['callsign' => 'PIRATE-1', 'freq' => '92.100 MHz', 'holder' => 'Unknown', 'expires' => '-', 'state' => 'anomaly'],
            ['callsign' => 'JAM-?', 'freq' => '1575.42 MHz', 'holder' => 'Unknown', 'expires' => '-', 'state' => 'anomaly'],
        ];
    }

    public function locations(): array
    {
        return [
            ['name' => 'Tomsk Buoy Array', 'country' => 'RU', 'lat' => 56.4847, 'lon' => 84.9476, 'sources' => 4, 'status' => 'live'],
            ['name' => 'WebSDR Twente', 'country' => 'NL', 'lat' => 52.0116, 'lon' => 4.3571, 'sources' => 1, 'status' => 'live'],
            ['name' => 'PiAware ORD', 'country' => 'US', 'lat' => 41.9742, 'lon' => -87.9073, 'sources' => 2, 'status' => 'live'],
            ['name' => 'Sentinel-Sat-3', 'country' => 'orbit', 'lat' => 0.0, 'lon' => 0.0, 'sources' => 1, 'status' => 'degraded'],
            ['name' => 'AIS Coast-7', 'country' => 'US', 'lat' => 32.7157, 'lon' => -117.1611, 'sources' => 3, 'status' => 'live'],
            ['name' => 'SDR-East', 'country' => 'US', 'lat' => 40.7128, 'lon' => -74.0060, 'sources' => 5, 'status' => 'live'],
        ];
    }

    public function sources(): array
    {
        return [
            ['id' => 'src-buoyiq-tomsk', 'name' => 'Buoy-IQ Tomsk', 'kind' => 'SDR + DF', 'org' => 'BuoyIQ', 'status' => 'live', 'last' => '14:12Z', 'reliability' => 0.93],
            ['id' => 'src-websdr-nl', 'name' => 'WebSDR Twente', 'kind' => 'WebSDR', 'org' => 'U. Twente', 'status' => 'live', 'last' => '14:11Z', 'reliability' => 0.88],
            ['id' => 'src-piaware-ord', 'name' => 'PiAware ORD', 'kind' => 'ADS-B', 'org' => 'Volunteer', 'status' => 'live', 'last' => '14:10Z', 'reliability' => 0.81],
            ['id' => 'src-sentinel-3', 'name' => 'Sentinel-Sat-3', 'kind' => 'Spaceborne', 'org' => 'Sentinel Co-op', 'status' => 'degraded', 'last' => '13:48Z', 'reliability' => 0.72],
            ['id' => 'src-ais-coast-7', 'name' => 'AIS Coast-7', 'kind' => 'AIS Receiver', 'org' => 'Harbor Authority', 'status' => 'live', 'last' => '14:09Z', 'reliability' => 0.86],
            ['id' => 'src-fcc-uls', 'name' => 'FCC ULS Mirror', 'kind' => 'Reference DB', 'org' => 'Public', 'status' => 'live', 'last' => '12:00Z', 'reliability' => 1.0],
        ];
    }

    public function complianceSummary(): array
    {
        return [
            ['label' => 'Compliant Allocations', 'value' => '294', 'tone' => 'gold'],
            ['label' => 'Open Findings', 'value' => '11', 'tone' => 'anomaly'],
            ['label' => 'Awaiting Review', 'value' => '6', 'tone' => 'signal'],
            ['label' => 'Auto-Resolved (7d)', 'value' => '38', 'tone' => 'slate'],
        ];
    }

    public function complianceFindings(): array
    {
        return [
            ['id' => 'CMP-104', 'event' => 'RF-2485', 'rule' => 'FCC Part 73 - unlicensed FM broadcast', 'severity' => 'high', 'opened' => '2026-05-04 13:42Z', 'state' => 'open'],
            ['id' => 'CMP-103', 'event' => 'RF-2483', 'rule' => 'ITU RR No. 15.21 - harmful interference (GNSS)', 'severity' => 'critical', 'opened' => '2026-05-04 12:50Z', 'state' => 'open'],
            ['id' => 'CMP-102', 'event' => 'RF-2487', 'rule' => 'FCC Part 80 - improper marine VHF use', 'severity' => 'high', 'opened' => '2026-05-04 14:13Z', 'state' => 'open'],
            ['id' => 'CMP-101', 'event' => 'RF-2481', 'rule' => 'ITU AIS - duplicate MMSI', 'severity' => 'medium', 'opened' => '2026-05-04 11:58Z', 'state' => 'investigating'],
            ['id' => 'CMP-097', 'event' => 'RF-2480', 'rule' => 'FCC Part 74 - STL path verified', 'severity' => 'info', 'opened' => '2026-05-04 11:21Z', 'state' => 'closed'],
        ];
    }
}
