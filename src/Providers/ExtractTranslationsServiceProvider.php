<?php

namespace StevenBraham\LaravelBladeTranslationsExtractor\Providers;

use Illuminate\Support\ServiceProvider;
use StevenBraham\LaravelBladeTranslationsExtractor\Console\Commands\ExtractTranslations;

class ExtractTranslationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExtractTranslations::class,
            ]);
        }
    }
}

