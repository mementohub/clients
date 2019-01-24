<?php

namespace IMemento\SDK\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use IMemento\SDK\Roles;
use IMemento\SDK\Responses\CollectionResponse;

class RolesTest extends TestCase
{
    protected function roles()
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(200, [], '{}')
        ]);

        $stack = HandlerStack::create($mock);
        $stack->push($history);
        return new Roles(['handler' => $stack]);
    }

    public function testExample()
    {
        $list = $this->roles()->listServices();
        $this->assertInstanceOf(CollectionResponse::class, $list);
    }
}
