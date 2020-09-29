<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Ratings;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RatingTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Ratings::class, $responses);
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
            ['listRatings', [], 'GET', 'ratings', CollectionResponse::class],
            ['createRating', [], 'POST', 'ratings', JsonResponse::class],
            ['showRating', [1], 'GET', 'ratings/1', JsonResponse::class],
            ['updateRating', [1], 'PUT', 'ratings/1', JsonResponse::class],
            ['destroyRating', [1], 'DELETE', 'ratings/1', JsonResponse::class],

            ['getResourceRatingAverage', [ [[1], '1', '1'] ], 'GET', 'ratings/average', JsonResponse::class],
        ];
    }
}
