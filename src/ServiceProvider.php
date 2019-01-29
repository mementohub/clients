<?php

namespace iMemento\Clients;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use iMemento\Clients\Handlers\MultiHandler;
use iMemento\Clients\Handlers\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Handler\Proxy;
use GuzzleHttp\Handler\StreamHandler;

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

        $this->app->bind(HandlerStack::class, function ($app) {
            $handler = null;
            if ((! function_exists('curl_multi_exec')) && (! function_exists('curl_exec'))) {
                throw new \RuntimeException('iMemento Clients require curl_exec and curl_multi_exec');
            }
            $handler = Proxy::wrapSync(resolve(MultiHandler::class), new CurlHandler());
            if (ini_get('allow_url_fopen')) {
                Proxy::wrapStreaming($handler, new StreamHandler());
            }
            return HandlerStack::create($handler);
        });
    }
}
