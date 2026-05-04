<?php

namespace App\Providers;

use App\Services\MispClient;
use App\Services\MockData;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MispClient::class, fn () => new MispClient(
            baseUrl: (string) config('services.misp.base_url'),
            apiKey: (string) config('services.misp.api_key'),
            verifyTls: (bool) config('services.misp.verify_tls'),
            useMock: (bool) config('services.misp.use_mock'),
        ));

        $this->app->singleton(MockData::class);
    }

    public function boot(): void
    {
        //
    }
}
