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

            $this->publishes([
                __DIR__ . '/../../config/laravel-blade-translations-extractor.php' => config_path('laravel-blade-translations-extractor.php'),
            ], 'extract-translations-config');
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laravel-blade-translations-extractor.php',
            'laravel-blade-translations-extractor'
        );
    }
}
