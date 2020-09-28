<?php

namespace iMemento\Clients\Tests\Clients;

use Illuminate\Foundation\Bus\PendingDispatch;
use iMemento\Clients\AbstractClient;
use iMemento\Clients\Events;
use iMemento\Clients\Responses\JsonResponse;

class EventsTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Events::class, $responses);
    }

    /**
     * @dataProvider endpointMappingsDataProvider
     */
    public function testItDispatchesEndpoints(
        $method,
        $arguments,
        $expectedMethod,
        $expectedPath
    ) {
        $base_uri = $this->client()->getBaseUri();

        $response = $this->client()->{$method}(...$arguments);
        $this->assertInstanceOf(PendingDispatch::class, $response);
    }

    public function endpointMappingsDataProvider()
    {
        return [
            ['emit', [['event' => 'test']], 'POST', 'listen'],   // queued
        ];
    }
}
