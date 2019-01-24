<?php

namespace IMemento\SDK;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{

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
