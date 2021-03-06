<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Roles;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
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

            ['listRoles',    [],        'GET',      'roles',        CollectionResponse::class],
            ['createRole',   [],        'POST',     'roles',        JsonResponse::class],
            ['showRole',     [2],       'GET',      'roles/2',      JsonResponse::class],
            ['updateRole',   [2],       'PUT',      'roles/2',      JsonResponse::class],
            ['destroyRole',  [2],       'DELETE',   'roles/2',      JsonResponse::class],

            ['showUserRoles',   [1],    'GET',      'users/1/roles',        CollectionResponse::class],
            ['attachUserRole',  [1, 2], 'POST',     'users/1/roles/2',      JsonResponse::class],
            ['detachUserRole',  [1, 2], 'DELETE',   'users/1/roles/2',      JsonResponse::class],

            ['getUserRolesByToken',  ['token'],     'GET',   'users/authentication',    CollectionResponse::class],
        ];
    }
}
