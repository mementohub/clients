<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Places;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PlacesTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Places::class, $responses);
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
            ['listPlaces',    [],     'GET',      'places',     CollectionResponse::class],
            ['createPlace',   [],     'POST',     'places',     JsonResponse::class],
            ['showPlace',     [1],    'GET',      'places/1',   JsonResponse::class],
            ['updatePlace',   [1],    'PUT',      'places/1',   JsonResponse::class],
            ['destroyPlace',  [1],    'DELETE',   'places/1',   JsonResponse::class],

            ['search', [['query' => 'abcd']], 'GET', 'search', CollectionResponse::class],
            ['place',  [1],      'GET', 'place/1',  JsonResponse::class],
        ];
    }
}
