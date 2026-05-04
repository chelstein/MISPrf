<?php

namespace App\Http\Controllers;

use App\Services\MockData;

class SourceController extends Controller
{
    public function index(MockData $data)
    {
        return view('sources', [
            'sources' => $data->sources(),
        ]);
    }
}
