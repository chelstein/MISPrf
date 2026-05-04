<?php

namespace App\Http\Controllers;

use App\Services\MockData;

class DashboardController extends Controller
{
    public function index(MockData $data)
    {
        return view('dashboard', [
            'metrics' => $data->dashboardMetrics(),
            'recentEvents' => array_slice($data->events(), 0, 6),
            'spectrum' => $data->spectrum(),
            'sightings' => $data->recentSightings(),
            'sites' => $data->locations(),
        ]);
    }
}
