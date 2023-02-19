<?php

namespace Brunocfalcao\Logger;

use Brunocfalcao\Logger\ApplicationLog;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrations();
        $this->publishResources();
    }

    public function register()
    {
        $this->mergeConfig();
    }

    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function publishResources(): void
    {
        $this->publishes([
            __DIR__.'/../resources/overrides/' => base_path('/'),
        ]);
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../resources/overrides/config/laravel-logger.php', 'laravel-logger');
    }

    protected function registerTrace(): void
    {
        $this->app->bind(ApplicationLog::class, function () {
            return ApplicationLog::make();
        });
    }
}
