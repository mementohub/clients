<?php

namespace iMemento\Clients;

use GuzzleHttp\Client as GuzzleClient;
use iMemento\Clients\Jobs\QueueRequest;
use iMemento\SDK\Auth\User;
use iMemento\Clients\Handlers\HandlerStack;
use iMemento\Clients\Middleware\Middleware;
use function Opis\Closure\serialize;

abstract class AbstractClient
{
    protected $mode = 'critical';           // critical || silent

    protected $authorization = 'service';   // none || user || service

    protected $should_queue = false;        // bool

    protected $config = [];

    protected $runtime = [];

    protected $middleware = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->resetRuntime();
        $this->resetMiddleware();
    }

    abstract public function getBaseUri();


    protected function resetRuntime()
    {
        $this->runtime = [
            'mode'          => [
                'preferred' => null,    // critical || silent
                'requested' => null,    // critical || silent
            ],
            'authorization' => [
                'requested' => null,    // none || token || user || service
            ],
            'token'         => null,
            'async'         => false,   // bool
        ];
    }

    protected function resetMiddleware()
    {
        $this->middleware = [
            'auth'    => null,
            'retry'   => null,
            'wrapper' => null,
        ];
    }

    protected function config()
    {
        $stack = resolve(HandlerStack::class);

        $defaults = [
            'base_uri' => $this->getBaseUri(),
            'handler'  => $stack,
            'headers'  => [
                'Accept'    => 'application/json',
            ],
        ];

        $config = array_merge($defaults, $this->config);

        $this->addMiddleware($config['handler']);

        return $config;
    }

    protected function addMiddleware($stack)
    {
        // this needs to be run first so that it overrides the http_errors behaviour
        $stack->unshift(Middleware::errors($this->runtime, $this->mode), 'errors');

        $this->middleware['auth'] = Middleware::auth($this->runtime, $this->authorization);

        // removing empty middleware
        $this->middleware = array_filter($this->middleware);

        foreach ($this->middleware as $name => $middleware) {
            $stack->remove($name);
            $stack->push($middleware, $name);
        }

        $this->resetMiddleware();
    }

    public function anonymously()
    {
        $this->runtime['authorization']['requested'] = 'none';
        return $this;
    }

    public function asService()
    {
        $this->runtime['authorization']['requested'] = 'service';
        return $this;
    }

    public function asUser()
    {
        $this->runtime['authorization']['requested'] = 'user';
        return $this;
    }

    public function as(User $user)
    {
        return $this->withToken($user->token);
    }

    public function withToken($token)
    {
        $this->runtime['authorization']['requested'] = 'token';
        $this->runtime['token'] = $token;
        return $this;
    }

    public function async()
    {
        $this->runtime['async'] = true;
        return $this;
    }

    public function queue()
    {
        $this->should_queue = true;
        return $this;
    }

    public function dontQueue()
    {
        $this->should_queue = false;
        return $this;
    }

    public function retries(int $allowed)
    {
        $this->middleware['retries'] = Middleware::retries($allowed);
        return $this;
    }

    public function silent()
    {
        $this->runtime['mode']['requested'] = 'silent';
        return $this;
    }

    public function critical()
    {
        $this->runtime['mode']['requested'] = 'critical';
        return $this;
    }

    protected function preferredSilent()
    {
        $this->runtime['mode']['preferred'] = 'silent';
        return $this;
    }

    protected function preferredCritical()
    {
        $this->runtime['mode']['preferred'] = 'critical';
        return $this;
    }

    protected function list($path, array $query = [])
    {
        return $this->collect()->request('GET', $path, [
            'query' => $query
        ]);
    }

    protected function post($path, array $attributes = [])
    {
        return $this->json()->request('POST', $path, [
            'json' => $attributes
        ]);
    }

    protected function get($path, array $query = [])
    {
        return $this->json()->request('GET', $path, [
            'query' => $query,
        ]);
    }

    protected function put($path, array $arguments = [])
    {
        return $this->json()->request('PUT', $path, [
            'json' => $arguments
        ]);
    }

    protected function delete($path)
    {
        return $this->json()->request('DELETE', $path);
    }

    protected function json()
    {
        $this->middleware['wrapper'] = Middleware::json();
        return $this;
    }

    protected function collect()
    {
        $this->middleware['wrapper'] = Middleware::collection();
        return $this;
    }

    protected function request($method, ...$args)
    {
        $request = $this->runtime['async'] ? 'requestAsync' : 'request';
        $config = $this->config();

        $client = new GuzzleClient($config);

        $this->resetRuntime();

        //special serialize that allows serialization of closures
        $config = serialize($config);

        return $this->should_queue ?
            QueueRequest::dispatch($config, $request, $method, ...$args) :
            $client->{$request}($method, ...$args);
    }
}
