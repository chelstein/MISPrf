<?php

return [
    'misp' => [
        'base_url' => env('MISP_BASE_URL'),
        'api_key' => env('MISP_API_KEY'),
        'verify_tls' => env('MISP_VERIFY_TLS', true),
        'use_mock' => env('RF_CONSOLE_USE_MOCK', true),
    ],
];
