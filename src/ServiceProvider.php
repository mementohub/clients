<?php

namespace iMemento\Clients;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use iMemento\Clients\Handlers\MultiHandler;

class ServiceProvider extends IlluminateServiceProvider
{
    public $singletons = [
        MultiHandler::class     => MultiHandler::class
    ];

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/imemento-sdk.php' => config_path('imemento-sdk.php')
        ], 'config');

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/imemento-sdk.php',
            'imemento-sdk'
        );
    }
}
