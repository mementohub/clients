<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Contracts;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContractsTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Contracts::class, $responses);
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
            [ 'listContracts',   [ ], 'GET',    'contracts',   CollectionResponse::class ],
            [ 'createContract',  [ ], 'POST',   'contracts',   JsonResponse::class       ],
            [ 'showContract',    [1], 'GET',    'contracts/1', JsonResponse::class       ],
            [ 'updateContract',  [1], 'PUT',    'contracts/1', JsonResponse::class       ],
            [ 'destroyContract', [1], 'DELETE', 'contracts/1', JsonResponse::class       ],
        ];
    }
}
