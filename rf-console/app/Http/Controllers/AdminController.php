<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin', [
            'misp' => [
                'base_url' => config('services.misp.base_url'),
                'use_mock' => config('services.misp.use_mock'),
                'verify_tls' => config('services.misp.verify_tls'),
                'api_key_set' => filled(config('services.misp.api_key')),
            ],
        ]);
    }
}
