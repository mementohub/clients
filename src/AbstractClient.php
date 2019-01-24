<?php

namespace IMemento\SDK;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use IMemento\SDK\Responses\CollectionResponse;
use IMemento\SDK\Responses\JsonResponse;
use IMemento\SDK\Responses\ErrorResponse;

abstract class AbstractClient
{
    protected $client;
    protected $resources;
    protected $mode = 'silent';
    private $request_mode = null;

    public function __construct(array $config = [])
    {
        $config = array_merge($this->getConfig(), $config);
        $this->client = new GuzzleClient($config);
    }

    protected function getConfig()
    {
        return [
            'base_uri' => $this->getBaseUri(),
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $this->getToken(),
            ]
        ];
    }

    abstract public function getBaseUri();

    protected function getToken()
    {
        return 'token';
    }

    protected function mode()
    {
        return $this->request_mode ?? $this->mode;
    }

    protected function shouldFail()
    {
        return ($this->mode() !== 'silent');
    }

    public function silent()
    {
        $this->request_mode = 'silent';
        return $this;
    }

    public function critical()
    {
        $this->request_mode = 'critical';
        return $this;
    }

    protected function reset()
    {
        $this->request_mode = null;
    }

    protected function list($path, array $query = [])
    {
        return $this->collect($this->get($path, [
            'query' => $query
        ]));
    }

    protected function create($path, array $attributes = [])
    {
        return $this->json($this->post($path, [
            'json' => $attributes
        ]));
    }

    protected function show($path)
    {
        return $this->json($this->get($path));
    }

    protected function update($path, array $arguments = [])
    {
        return $this->json($this->put($path, [
            'json' => $arguments
        ]));
    }

    protected function destroy($path)
    {
        return $this->json($this->delete($path));
    }

    protected function json(Response $response)
    {
        return new JsonResponse($response);
    }

    protected function collect(Response $response)
    {
        return new CollectionResponse($response);
    }

    /**
     * Forward all unkown methods to the Guzzle Client
     */
    public function __call($method, $args)
    {
        return $this->forwardToGuzzle($method, $args);
    }

    protected function forwardToGuzzle($method, $args)
    {
        $shouldFail = $this->shouldFail();
        $this->reset();
        try {
            return $this->client->{$method}(...$args);
        } catch (GuzzleException $e) {
            if ($shouldFail) {
                throw $e;
            }
            return new ErrorResponse($e);
        }
    }
}
