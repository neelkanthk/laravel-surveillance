<?php

namespace Neelkanth\Laravel\Surveillance\Providers;

use Illuminate\Support\ServiceProvider;
use Neelkanth\Laravel\Surveillance\Services\Surveillance;
use Neelkanth\Laravel\Surveillance\Http\Middleware\SurveillanceMiddleware;
use Illuminate\Routing\Router;

class SurveillanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            realpath(__DIR__ . '/../../config/surveillance.php'),
            'surveillance'
        );
        //Register Facade
        $this->app->bind('surveillance', function ($app) {
            return new Surveillance();
        });

        $managerRepository = config("surveillance.manager-repository");
        $this->app->bind(
            'Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceManagerInterface',
            $managerRepository
        );

        $logRepository = config("surveillance.log-repository");
        $this->app->bind(
            'Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceLogInterface',
            $logRepository
        );
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                "Neelkanth\Laravel\Surveillance\Console\Commands\SurveillanceEnable",
                "Neelkanth\Laravel\Surveillance\Console\Commands\SurveillanceDisable",
                "Neelkanth\Laravel\Surveillance\Console\Commands\SurveillanceBlock",
                "Neelkanth\Laravel\Surveillance\Console\Commands\SurveillanceUnblock",
                "Neelkanth\Laravel\Surveillance\Console\Commands\SurveillanceRemoveRecord"
            ]);

            //php artisan vendor:publish --provider="Neelkanth\Laravel\Surveillance\Providers\SurveillanceServiceProvider" --tag="migrations"
            $this->publishes([
                realpath(__DIR__ . '/../../database/migrations/create_surveillance_managers_table.php.stub') => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_surveillance_managers_table.php'),
                realpath(__DIR__ . '/../../database/migrations/create_surveillance_logs_table.php.stub') => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_surveillance_logs_table.php')
            ], 'migrations');

            //php artisan vendor:publish --provider="Neelkanth\Laravel\Surveillance\Providers\SurveillanceServiceProvider" --tag="config"
            $this->publishes([
                realpath(__DIR__ . '/../../config/surveillance.php') => config_path('surveillance.php'),
            ], 'config');

            //php artisan vendor:publish --provider="Neelkanth\Laravel\Surveillance\Providers\SurveillanceServiceProvider" --tag="lang"
            $this->publishes([
                realpath(__DIR__ . '/../../resources/lang/en/surveillance.php') => resource_path('lang/en/surveillance.php'),
            ], 'lang');
        }

        //Register Middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('surveillance', SurveillanceMiddleware::class);
    }
}
