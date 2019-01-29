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
use iMemento\SDK\Auth\User;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Log;

class ClientTest extends TestCase
{
    protected $handler;

    protected function client(array $responses = [])
    {
        if (count($responses) == 0) {
            $responses[] = new Response(200, [], '{}');
        }

        $helper = m::mock('alias:iMemento\SDK\Auth\Helper');
        $helper->shouldReceive('authenticate')->andReturn('service.token.test');

        $auth = new User();
        $auth->token = 'user.token.test';
        $this->actingAs($auth);

        $this->history = [];
        $history = Middleware::history($this->history);
        $this->handler = new MockHandler($responses);

        $stack = HandlerStack::create($this->handler);
        $stack->push($history);

        return new ClientStub(['handler' => $stack]);
    }

    protected function history()
    {
        return collect($this->history);
    }

    /**
     * structure behaviour
     */
    public function testItCanRetrieveOriginalBody()
    {
        $json = '{"foo":"bar"}';
        $response = $this->client([
            new Response(200, [], $json)
        ])->call();
        $this->assertEquals('bar', $response->foo);
        $this->assertEquals($json, $response->response()->getBody()->getContents());
        $this->assertEquals($json, (string)$response->response()->getBody());
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
     * Error handling
     */
    public function testItFailsOnErrors()
    {
        $this->expectException(BadResponseException::class);
        $client = $this->client([
            new Response(500, [], '{}'),
        ]);

        $client->call();
    }

    public function testItHandlesCriticalCalls()
    {
        $response = $this->client()->critical()->call();
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testItHandlesSilentCalls()
    {
        Log::shouldReceive('debug')->once();
        $response = $this->client([
            new Response(500, [], '{}'),
        ])->silent()->call();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * Retries behaviour
     */
    public function testItRetries()
    {
        $client = $this->client([
            new Response(400, [], '{}'),
            new Response(401, [], '{}'),
            new Response(402, [], '{}'),
            new Response(403, [], '{}'),
            new Response(404, [], '{}'),
            new Response(502, [], '{}'),
            new Response(504, [], '{}'),
            new Response(200, [], '{}'),
        ]);

        $client->retries(7)->call();
    }

    public function testItFailsAfterRetries()
    {
        $this->expectException(BadResponseException::class);
        $client = $this->client([
            new Response(400, [], '{}'),
            new Response(401, [], '{}'),
            new Response(402, [], '{}'),
            new Response(200, [], '{}'),
        ]);

        $client->retries(2)->call();
    }

    /**
     * Authentication behaviour
     */
    public function testDefaultAuthorization()
    {
        $this->client()->call();
        $history = $this->history()->pop();

        $this->assertToken($history, 'service.token.test');
    }

    public function testAsUserAuthorization()
    {
        $this->client()->asUser()->call();
        $history = $this->history()->pop();

        $this->assertToken($history, 'user.token.test');    
    }

    public function testAsCustomUserAuthorization()
    {
        $user = new User();
        $user->token = 'new.user.token.test';

        $this->client()->as($user)->call();
        $history = $this->history()->pop();

        $this->assertToken($history, 'new.user.token.test');    
    }

    public function testCustomTokenAuthorization()
    {
        $this->client()->withToken('some.token.test')->call();
        $history = $this->history()->pop();

        $this->assertToken($history, 'some.token.test');   
    }

    public function testAnonymousCalls()
    {
        $this->client()->anonymously()->call();
        $history = $this->history()->pop();

        $this->assertToken($history, null);   
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


    public function assertToken($transaction, $challenge = null)
    {
        $header = $this->handler->getLastRequest()->getHeader('Authentication');
        if (! $challenge) {
            return $this->assertCount(0, $header);
        }
        return $this->assertEquals($header[0], 'Bearer ' . $challenge);
    }
}
