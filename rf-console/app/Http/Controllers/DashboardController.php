<?php

namespace App\Http\Controllers;

use App\Services\MispClient;
use App\Services\MispTransformer;
use App\Services\MockData;

class DashboardController extends Controller
{
    public function index(MispClient $client, MispTransformer $tx, MockData $mock)
    {
        $envelopes = $client->searchEvents(['limit' => 50]);
        $events = $tx->events($envelopes);

        $sightingEnvelopes = $client->searchSightings(['limit' => 8]);
        $sightings = $tx->sightings($sightingEnvelopes);
        if (empty($sightings)) {
            $sightings = $mock->recentSightings();
        }

        return view('dashboard', [
            'metrics' => $this->deriveMetrics($events),
            'recentEvents' => array_slice($events, 0, 6),
            'spectrum' => $mock->spectrum(),
            'sightings' => $sightings,
            'sites' => $mock->locations(),
        ]);
    }

    private function deriveMetrics(array $events): array
    {
        $active = count($events);
        $verified = count(array_filter($events, fn ($e) => $e['status'] === 'verified'));
        $anomalies = count(array_filter($events, fn ($e) => $e['status'] === 'anomaly'));
        $sources = count(array_unique(array_map(fn ($e) => $e['source'], $events)));

        return [
            ['label' => 'Active RF Events', 'value' => (string) $active, 'delta' => 'live', 'tone' => 'signal'],
            ['label' => 'Verified Licensed', 'value' => (string) $verified, 'delta' => '', 'tone' => 'gold'],
            ['label' => 'Anomalies Open', 'value' => (string) $anomalies, 'delta' => '', 'tone' => 'anomaly'],
            ['label' => 'Sources Online', 'value' => (string) $sources, 'delta' => '', 'tone' => 'slate'],
        ];
    }
}
