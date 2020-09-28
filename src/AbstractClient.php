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

    protected $config_name = null;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->resetRuntime();
        $this->resetMiddleware();
    }

    public function getBaseUri()
    {
        return $this->getConfigValue('base_uri');
    }

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

    protected function getConfigName()
    {
        $name = $this->config_name ?? (new \ReflectionClass($this))->getShortName();
        return strtolower($name);
    }

    protected function getConfigValue($name, $default = null)
    {
        $config = array_merge($this->defaultConfig(), $this->config);
        if (array_key_exists($name, $config)) {
            return $config[$name];
        }
        return $default;
    }

    protected function defaultConfig()
    {
        $stack = resolve(HandlerStack::class);

        $defaults = [
            'handler'  => $stack,
            'headers'  => [
                'Accept'    => 'application/json',
            ],
            'query' => [],
            'json'  => []
        ];

        $fromConfigFile = config('imemento-sdk.' . $this->getConfigName(), []);

        return array_merge($defaults, $fromConfigFile);
    }

    protected function config()
    {
        $config = array_merge($this->defaultConfig(), $this->config);

        $this->addMiddleware($config['handler']);

        return $config;
    }

    protected function addMiddleware($stack)
    {
        // this needs to be run first so that it overrides the http_errors behaviour
        $stack->unshift(Middleware::errors($this->failureMode()), 'errors');

        $this->middleware['auth'] = Middleware::auth($this->authMethod(), data_get($this->runtime, 'token'));

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

    public function retries(int $allowed, callable $delay = null)
    {
        $this->middleware['retries'] = Middleware::retries($allowed, $delay);
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
            'query' => array_merge($this->getConfigValue('query', []), $query)
        ]);
    }

    protected function post($path, array $attributes = [])
    {
        return $this->json()->request('POST', $path, [
            'json' => array_merge($this->getConfigValue('json', []), $attributes)
        ]);
    }

    protected function get($path, array $query = [])
    {
        return $this->json()->request('GET', $path, [
            'query' => array_merge($this->getConfigValue('query', []), $query)
        ]);
    }

    protected function put($path, array $arguments = [])
    {
        return $this->json()->request('PUT', $path, [
            'json' => array_merge($this->getConfigValue('json', []), $arguments)
        ]);
    }

    protected function delete($path)
    {
        return $this->json()->request('DELETE', $path);
    }

    protected function json()
    {
        $this->middleware['wrapper'] = Middleware::json($this->failureMode());
        return $this;
    }

    protected function collect()
    {
        $this->middleware['wrapper'] = Middleware::collection($this->failureMode());
        return $this;
    }

    protected function failureMode()
    {
        return data_get($this->runtime, 'mode.requested')
            ?? data_get($this->runtime, 'mode.preferred')
            ?? $this->mode
            ?? 'critical';
    }

    protected function authMethod()
    {
        return data_get($this->runtime, 'authorization.requested')
            ?? $this->authorization
            ?? 'none';
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
