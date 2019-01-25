<?php

namespace iMemento\Clients;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;
use iMemento\Clients\Responses\ErrorResponse;
use Illuminate\Contracts\Auth\Authenticatable;

abstract class AbstractClient
{
    protected $mode = 'critical';   // critical || silent

    protected $authorization = 'service';   // none || user || service

    protected $config = [];

    protected $runtime = [];

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
        $current = [
            'base_uri' => $this->getBaseUri(),
            RequestOptions::HEADERS => $this->getHeaders(),
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

    public function as(Authenticatable $user)
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
                return 'user.token';
                break;
            case 'service':
                return 'service.token';
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
        return $this->collect($this->request('GET', $path, [
            'query' => $query
        ]));
    }

    protected function create($path, array $attributes = [])
    {
        return $this->json($this->request('POST', $path, [
            'json' => $attributes
        ]));
    }

    protected function show($path)
    {
        return $this->json($this->request('GET', $path));
    }

    protected function update($path, array $arguments = [])
    {
        return $this->json($this->request('PUT', $path, [
            'json' => $arguments
        ]));
    }

    protected function destroy($path)
    {
        return $this->json($this->request('DELETE', $path));
    }

    protected function json(Response $response)
    {
        return new JsonResponse($response);
    }

    protected function collect(Response $response)
    {
        return new CollectionResponse($response);
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
