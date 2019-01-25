<?php

namespace IMemento\SDK\Tests\Clients;

use IMemento\SDK\AbstractClient;
use IMemento\SDK\Roles;
use IMemento\SDK\Responses\CollectionResponse;
use IMemento\SDK\Responses\JsonResponse;

class RolesTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Roles::class, $responses);
    }

    /**
     * @dataProvider endpointMappingsDataProvider
     */
    public function testItMakesTheRightCalls() {
        return $this->checkEndpointMappings(...func_get_args());
    }

    public function endpointMappingsDataProvider()
    {
        return [
            ['listServices',    [],     'GET',      'services',     CollectionResponse::class],
            ['createService',   [],     'POST',     'services',     JsonResponse::class],
            ['showService',     [2],    'GET',      'services/2',   JsonResponse::class],
            ['updateService',   [2],    'PUT',      'services/2',   JsonResponse::class],
            ['destroyService',  [2],    'DELETE',   'services/2',   JsonResponse::class],
        ];
    }
}
