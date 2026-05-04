<?php

namespace App\Http\Controllers;

use App\Services\MispClient;

class AdminController extends Controller
{
    public function index(MispClient $client)
    {
        $version = null;
        $error = null;
        $reachable = false;

        try {
            $version = $client->getServerVersion();
            $reachable = true;
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        return view('admin', [
            'misp' => [
                'base_url' => config('services.misp.base_url'),
                'use_mock' => config('services.misp.use_mock'),
                'verify_tls' => config('services.misp.verify_tls'),
                'api_key_set' => filled(config('services.misp.api_key')),
            ],
            'version' => $version,
            'reachable' => $reachable,
            'error' => $error,
        ]);
    }
}
