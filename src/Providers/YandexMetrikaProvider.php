<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Providers;

use Illuminate\Support\ServiceProvider;

class YandexMetrikaProvider extends ServiceProvider
{
    /**
     * Additional service providers to register for the environment.
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/config.php'), 'yandex-metrika-api');

        $this->app->bind('yandexMetrikaApi', function () {
            return new \Rovereto\YandexMetrika\YandexMetrika;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('yandex-metrika-api.php'),
        ], 'config');
    }
}
