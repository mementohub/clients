<?php

return [
    'comments' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_COMMENTS', 'services-comments') . '/api/'
    ],
    'contracts' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_CONTRACTS', 'services-contracts') . '/api/'
    ],
    'logs' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_ACTIVITY_LOG', 'services-activity-log') . '/api/'
    ],
    'eventbus' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_EVENTBUS', 'services-event-bus') . '/api/'
    ],
    'ratings' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_RATINGS', 'services-ratings') . '/api/'
    ],
    'roles' => [
        'base_uri'  => env('ENDPOINT_INTERNAL_SERVICES_ROLES', 'services-roles') . '/api/'
    ],
    'places' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_PLACES', 'services-places') . '/api/'
    ],
    'profiles' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_PROFILES', 'services-profiles') . '/api/'
    ]
];
