<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Notifications;
use iMemento\Clients\Responses\JsonResponse;

class NotificationsTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Notifications::class, $responses);
    }

    /**
     * @dataProvider endpointMappingsDataProvider
     */
    public function testItMakesTheRightCalls()
    {
        return $this->checkEndpointMappings(...func_get_args());
    }

    public function endpointMappingsDataProvider()
    {
        return [
            [ 'email',   [ ], 'POST',    'email',   JsonResponse::class ],
            [ 'sms',     [ ], 'POST',    'sms',     JsonResponse::class ],
            [ 'slack',   [ ], 'POST',    'slack',   JsonResponse::class ],
        ];
    }
}
