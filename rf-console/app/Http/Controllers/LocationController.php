<?php

namespace App\Http\Controllers;

use App\Services\MockData;

class LocationController extends Controller
{
    public function index(MockData $data)
    {
        return view('locations', [
            'locations' => $data->locations(),
        ]);
    }
}
