<?php

use App\Services\MispClient;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('misp:ping', function (MispClient $client) {
    $this->info('rf-console MISP backend probe');
    $this->line('  mode: '.($client->isMock() ? 'MOCK' : 'LIVE'));

    try {
        $v = $client->getServerVersion();
    } catch (\Throwable $e) {
        $this->error('  ping: FAIL - '.$e->getMessage());
        return 1;
    }

    $this->line('  version: '.($v['version'] ?? 'unknown'));
    if (array_key_exists('perm_sync', $v)) {
        $this->line('  perm_sync: '.($v['perm_sync'] ? 'yes' : 'no'));
    }
    if (! empty($v['mock'])) {
        $this->warn('  (mock backend - set RF_CONSOLE_USE_MOCK=false to hit a live MISP)');
    }
    $this->info('OK');
    return 0;
})->purpose('Probe the configured MISP backend and print version info');
