<?php

namespace App\Http\Controllers;

use App\Services\MockData;

class FrequencyController extends Controller
{
    public function index(MockData $data)
    {
        return view('frequencies', [
            'bands' => $data->frequencyBands(),
            'allocations' => $data->allocations(),
        ]);
    }
}
