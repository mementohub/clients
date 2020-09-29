<?php

namespace iMemento\Clients\Tests\Clients;

use iMemento\Clients\AbstractClient;
use iMemento\Clients\Translations;
use iMemento\Clients\Responses\CollectionResponse;
use iMemento\Clients\Responses\JsonResponse;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TranslationsTest extends TestCase
{
    protected function client(array $responses = []): AbstractClient
    {
        return $this->mockClient(Translations::class, $responses);
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
            [ 'listPlatforms',   [ ], 'GET',    'platforms',   CollectionResponse::class ],
            [ 'createPlatform',  [ ], 'POST',   'platforms',   JsonResponse::class       ],
            [ 'showPlatform',    [1], 'GET',    'platforms/1', JsonResponse::class       ],
            [ 'updatePlatform',  [1], 'PUT',    'platforms/1', JsonResponse::class       ],
            [ 'destroyPlatform', [1], 'DELETE', 'platforms/1', JsonResponse::class       ],

            [ 'listLanguages',   [ ], 'GET',    'languages',   CollectionResponse::class ],
            [ 'createLanguage',  [ ], 'POST',   'languages',   JsonResponse::class       ],
            [ 'showLanguage',    [1], 'GET',    'languages/1', JsonResponse::class       ],
            [ 'updateLanguage',  [1], 'PUT',    'languages/1', JsonResponse::class       ],
            [ 'destroyLanguage', [1], 'DELETE', 'languages/1', JsonResponse::class       ],

            [ 'listIdentifiers',   [ ], 'GET',    'identifiers',   CollectionResponse::class ],
            [ 'createIdentifier',  [ ], 'POST',   'identifiers',   JsonResponse::class       ],
            [ 'showIdentifier',    [1], 'GET',    'identifiers/1', JsonResponse::class       ],
            [ 'updateIdentifier',  [1], 'PUT',    'identifiers/1', JsonResponse::class       ],
            [ 'destroyIdentifier', [1], 'DELETE', 'identifiers/1', JsonResponse::class       ],

            [ 'listPages',   [ ], 'GET',    'pages',   CollectionResponse::class ],
            [ 'createPage',  [ ], 'POST',   'pages',   JsonResponse::class       ],
            [ 'showPage',    [1], 'GET',    'pages/1', JsonResponse::class       ],
            [ 'updatePage',  [1], 'PUT',    'pages/1', JsonResponse::class       ],
            [ 'destroyPage', [1], 'DELETE', 'pages/1', JsonResponse::class       ],

            [ 'getCoverage',                [1],              'GET', 'coverage/1',                    JsonResponse::class ],
            [ 'getUntranslatedIdentifiers', ['code'],         'GET', 'identifiers/untranslated/code', JsonResponse::class ],
            [ 'getAllIdentifiers',          ['slug', 'code'], 'GET', 'identifiers/slug/code',         JsonResponse::class ],
            [ 'updateIdentifiers',          [['key' => 'value']],      'PUT', 'identifiers/translations',      JsonResponse::class ],
        ];
    }
}
