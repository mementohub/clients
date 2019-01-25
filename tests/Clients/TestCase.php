<?php

namespace iMemento\Clients\Tests\Clients;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use iMemento\Clients\AbstractClient;
use iMemento\Clients\Tests\TestCase as BaseTestCase;
use Mockery as m;

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

        $helper = m::mock('alias:iMemento\SDK\Auth\Helper');
        $helper->shouldReceive('authenticate')->andReturn('service.token.test');

        $auth = m::mock('alias:iMemento\SDK\Auth\User');
        $auth->token = 'user.token.test';

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
