<?php

namespace App\Http\Controllers;

use App\Services\MockData;

class AttributeController extends Controller
{
    public function index(MockData $data)
    {
        return view('attributes', [
            'attributes' => $data->allAttributes(),
        ]);
    }
}
