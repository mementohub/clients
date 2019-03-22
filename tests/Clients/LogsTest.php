<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Logs;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

class LogTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Logs::class, $responses);
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
            [ 'listLogs',          [ ],             'GET',    'logs',           CollectionResponse::class ],
            [ 'createLog',         [ ],             'POST',   'logs',           JsonResponse::class       ],
            [ 'showLog',           [1],             'GET',    'logs/1',         JsonResponse::class       ],
            [ 'updateLog',         [1],             'PUT',    'logs/1',         JsonResponse::class       ],
            [ 'destroyLog',        [1],             'DELETE', 'logs/1',         JsonResponse::class       ],
        ];
    }
}
