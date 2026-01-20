<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Console\Commands\SeedCommand;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend('command.seed', function () {
            return new SeedCommand($this->app['db']);
        });
    }

    public function boot(): void
    {
        // Force HTTPS when behind ngrok or other reverse proxies
        if (request()->hasHeader('X-Forwarded-Proto') && request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
