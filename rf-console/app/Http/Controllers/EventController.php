<?php

namespace App\Http\Controllers;

use App\Services\MispClient;
use App\Services\MispTransformer;
use App\Services\MockData;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    public function index(MispClient $client, MispTransformer $tx)
    {
        $envelopes = $client->searchEvents(['limit' => 100]);
        return view('events.index', [
            'events' => $tx->events($envelopes),
        ]);
    }

    public function show(string $id, MispClient $client, MispTransformer $tx, MockData $mock)
    {
        $rawId = $tx->rawId($id);
        $eventInner = $client->getEvent($rawId);
        if (! $eventInner) {
            throw new NotFoundHttpException('RF event '.$id.' not found');
        }
        $envelope = ['Event' => $eventInner];
        $event = $tx->event($envelope);

        $attributes = $tx->attributes(
            $eventInner['Attribute'] ?? [],
            $rawId,
            $event['info']
        );

        try {
            $sightingEnvelopes = $client->searchSightings(['context' => 'event', 'id' => $rawId]);
        } catch (\Throwable) {
            $sightingEnvelopes = [];
        }
        $sightings = $tx->sightings($sightingEnvelopes);

        return view('events.show', [
            'event' => $event,
            'attributes' => $attributes,
            'sightings' => $sightings,
            'spectrum' => $mock->spectrum(),
            'timeline' => $tx->timelineFor($envelope),
        ]);
    }
}
