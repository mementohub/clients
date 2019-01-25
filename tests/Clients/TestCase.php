<?php

namespace IMemento\SDK\Tests\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use IMemento\SDK\AbstractClient;
use IMemento\SDK\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected $history = [];

    abstract protected function client(array $responses = []): AbstractClient;

    abstract public function endpointMappingsDataProvider();

    protected function checkEndpointMappings(
        $method,
        $arguments,
        $expectedMethod,
        $expectedPath,
        $expectedResponse
    ) {
        $base_uri = $this->client()->getBaseUri();

        $response = $this->client()->{$method}(...$arguments);
        $this->assertInstanceOf($expectedResponse, $response);

        $last_call = $this->history()->pop();
        $this->assertEquals($expectedMethod, $last_call['request']->getMethod());
        $this->assertEquals($base_uri . $expectedPath, $last_call['request']->getUri()->getPath());
    }

    protected function mockClient($class, array $responses = [])
    {
        if (count($responses) == 0) {
            $responses[] = new Response(200, [], '{}');
        }

        $this->history = [];
        $history = Middleware::history($this->history);
        $mock = new MockHandler($responses);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        return new $class(['handler' => $stack]);
    }

    protected function history()
    {
        return collect($this->history);
    }
}
