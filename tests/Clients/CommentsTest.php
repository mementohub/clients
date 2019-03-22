<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Comments;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

class CommentTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Comments::class, $responses);
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
            [ 'listComments',          [ ],             'GET',    'comments',           CollectionResponse::class ],
            [ 'createComment',         [ ],             'POST',   'comments',           JsonResponse::class       ],
            [ 'showComment',           [1],             'GET',    'comments/1',         JsonResponse::class       ],
            [ 'updateComment',         [1],             'PUT',    'comments/1',         JsonResponse::class       ],
            [ 'destroyComment',        [1],             'DELETE', 'comments/1',         JsonResponse::class       ],

            [ 'approveComment',        [1],             'PUT',    'comments/1/approve', JsonResponse::class       ],
            [ 'getResourceCommentSum', ['1', '1', '1'], 'GET',    'comments/sum',       JsonResponse::class       ],
        ];
    }
}
