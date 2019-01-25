<?php

namespace iMemento\Clients;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\HandlerStack;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;
use iMemento\Clients\Responses\ErrorResponse;
use iMemento\SDK\Auth\User;
use GuzzleHttp\Middleware;
use iMemento\Clients\Handlers\MultiHandler;

abstract class AbstractClient
{
    protected $mode = 'critical';           // critical || silent

    protected $authorization = 'service';   // none || user || service

    protected $config = [];

    protected $runtime = [];

    protected $middleware = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->resetRuntime();
    }

    abstract public function getBaseUri();


    protected function resetRuntime()
    {
        $this->runtime = [
            'mode' => [
                'preferred' => null,    // critical || silent
                'requested' => null,    // critical || silent
            ],
            'authorization' => [
                'requested' => null,    // none || token || user || service
            ],
            'token' => null,
            'async' => false,           // bool
        ];
    }

    protected function config()
    {
        $stack = new HandlerStack();
        $handler = resolve(MultiHandler::class);
        $stack->setHandler($handler);

        $current = [
            'base_uri' => $this->getBaseUri(),
            RequestOptions::HEADERS => $this->getHeaders(),
            'handler'   => $stack,
        ];

        // merging the config received in the constructor
        foreach ($this->config as $key => $value) {
            if (! array_key_exists($key, $current)) {
                $current[$key] = $value;
                continue;
            }
            if (! is_array($current[$key])) {
                $current[$key] = $value;
                continue;
            }
            if (! is_array($this->config[$key])) {
                $this->config[$key] = [$this->config[$key]];
            }
            array_merge($current[$key], $this->config[$key]);
        }

        $this->addMiddleware($current['handler']);

        return $current;
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => $this->getAuthorizationHeader()
        ];
    }

    protected function getAuthorizationHeader()
    {
        if (! $this->requiresAuthorization()) {
            return '';
        }
        return 'Bearer ' . $this->token();
    }

    protected function requiresAuthorization()
    {
        $value = $this->runtime['authorization']['requested'] ?? $this->authorization;
        return ($value !== 'none'); 
    }

    protected function addMiddleware($stack)
    {
        foreach ($this->middleware as $name => $middleware) {
            $stack->remove($name);
            $stack->push($middleware, $name);
        }
        $this->middleware = [];
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

    protected function token()
    {
        $authorization = $this->runtime['authorization']['requested'] ?? $this->authorization;
        switch ($authorization) {
            case 'token':
                return $this->runtime['token'];
                break;
            case 'user':
                return auth()->user()->token;
                break;
            case 'service':
                return \iMemento\SDK\Auth\Helper::authenticate();
                break;
            default:
                return '';
                break;
        }
        return '';
    }

    public function async()
    {
        $this->runtime['async'] = true;
        return $this;
    }

    protected function mode()
    {
        return $this->runtime['mode']['requested']
            ?? $this->runtime['mode']['preferred']
            ?? $this->mode
        ;
    }

    protected function shouldFail()
    {
        return ($this->mode() !== 'silent');
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

    protected function create($path, array $attributes = [])
    {
        return $this->json()->request('POST', $path, [
            'json' => $attributes
        ]);
    }

    protected function show($path)
    {
        return $this->json()->request('GET', $path);
    }

    protected function update($path, array $arguments = [])
    {
        return $this->json()->request('PUT', $path, [
            'json' => $arguments
        ]);
    }

    protected function destroy($path)
    {
        return $this->json()->request('DELETE', $path);
    }

    protected function json()
    {
        $this->middleware['wrapper'] = Middleware::mapResponse(
            function (ResponseInterface $response) {
                return new JsonResponse($response);
            }
        );
        return $this;
    }

    protected function collect()
    {
        $this->middleware['wrapper'] = Middleware::mapResponse(
            function (ResponseInterface $response) {
                return new CollectionResponse($response);
            }
        );
        return $this;
    }

    protected function request($method, ...$args)
    {
        $shouldFail = $this->shouldFail();
        $request = $this->runtime['async'] ? 'requestAsync' : 'request';

        $this->resetRuntime();

        $client = new GuzzleClient($this->config());
        try {
            return $client->{$request}($method, ...$args);
        } catch (GuzzleException $e) {
            if ($shouldFail) {
                throw $e;
            }
            return new ErrorResponse($e);
        }
    }
}
