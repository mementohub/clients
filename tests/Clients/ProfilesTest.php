<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Profiles;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

class ProfilesTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Profiles::class, $responses);
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
            ['listUsers',    [],     'GET',      'users',     CollectionResponse::class],
            ['createUser',   [],     'POST',     'users',     JsonResponse::class],
            ['showUser',     [2],    'GET',      'users/2',   JsonResponse::class],
            ['updateUser',   [2],    'PUT',      'users/2',   JsonResponse::class],
            ['destroyUser',  [2],    'DELETE',   'users/2',   JsonResponse::class],

            ['listOrganizations',    [],        'GET',      'organizations',        CollectionResponse::class],
            ['createOrganization',   [],        'POST',     'organizations',        JsonResponse::class],
            ['showOrganization',     [2],       'GET',      'organizations/2',      JsonResponse::class],
            ['updateOrganization',   [2],       'PUT',      'organizations/2',      JsonResponse::class],
            ['destroyOrganization',  [2],       'DELETE',   'organizations/2',      JsonResponse::class],

            ['listBillings',    [],        'GET',      'billings',        CollectionResponse::class],
            ['createBilling',   [],        'POST',     'billings',        JsonResponse::class],
            ['showBilling',     [2],       'GET',      'billings/2',      JsonResponse::class],
            ['updateBilling',   [2],       'PUT',      'billings/2',      JsonResponse::class],
            ['destroyBilling',  [2],       'DELETE',   'billings/2',      JsonResponse::class],

            ['getUserProfile',                  [],         'GET',      'users/me',                 JsonResponse::class],
            ['getUserOrganizationsByToken',     ['token'],  'GET',      'users/authentication',     CollectionResponse::class],
            ['getUserBilling',                  [1],        'GET',      'users/1/billing',          JsonResponse::class],

            ['getOrganizationBilling',          [1],        'GET',      'organizations/1/billing',  JsonResponse::class],
        ];
    }
}
