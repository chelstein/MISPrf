<?php

namespace App\Http\Controllers;

use App\Services\MockData;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    public function index(MockData $data)
    {
        return view('events.index', [
            'events' => $data->events(),
        ]);
    }

    public function show(string $id, MockData $data)
    {
        $event = collect($data->events())->firstWhere('id', $id);
        if (! $event) {
            throw new NotFoundHttpException('RF event '.$id.' not found');
        }

        return view('events.show', [
            'event' => $event,
            'attributes' => $data->attributesForEvent($id),
            'sightings' => $data->sightingsForEvent($id),
            'spectrum' => $data->spectrum(),
            'timeline' => $data->timelineForEvent($id),
        ]);
    }
}
