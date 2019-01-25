<?php

namespace iMemento\Clients\Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use iMemento\Clients\Tests\TestCase;
use Mockery as m;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise as PromiseSpace;
use iMemento\Clients\Responses\JsonResponse;

class ClientTest extends TestCase
{
    protected function client(array $responses = [])
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

        return new ClientStub(['handler' => $stack]);
    }

    protected function history()
    {
        return collect($this->history);
    }

    /**
     * Mode behaviour tests
     */
    public function testDefaultMode()
    {
        $this->assertEquals('critical', $this->client()->getMode());
    }

    public function testRequestedMode()
    {
        $this->assertEquals('silent', $this->client()->silent()->getMode());
    }

    public function testPreferredMode()
    {
        $this->assertEquals('silent', $this->client()->preferredSilentCall()->getMode());
    }

    public function testModePrecedence()
    {
        $this->assertEquals('critical', $this->client()->critical()->preferredSilentCall()->getMode());
    }

    public function testModeOverwrite()
    {
        $this->assertEquals('silent', $this->client()->critical()->silentCall()->getMode());
    }

    /**
     * Authentication behaviour
     */
    public function codeSamples()
    {

        return;
        $client = $this->client();

        // Regular calls
        $client->call();

        // Unauthenticated calls (for publicly available endpoints)
        $client->anonymously()->call();

        // With custom token (for authenticated users)
        $client->withToken($token)->call();

        // Will use the app credentials
        $client->asService()->call();
    }

    /**
     * Async behaviour
     */
    public function testAsyncMethod()
    {
        $client = $this->client();

        $promise = $client->async()->call();

        $this->assertInstanceOf(Promise::class, $promise);

        $response = $promise->wait();

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testMultipleAsyncCalls()
    {
        $client = $this->client([
            new Response(200, [], '{}'),
            new Response(200, [], '{}'),
            new Response(200, [], '{}')
        ]);

        $promises = [
            $client->async()->call(),
            $client->async()->call(),
            $client->async()->call(),
        ];

        $results = PromiseSpace\settle($promises)->wait();

        $this->assertCount(3, $results);

        foreach ($results as $result) {
            $this->assertInstanceOf(JsonResponse::class, $result['value']);
        }
    }

}
