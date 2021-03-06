<?php

return [
    'bookings' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_BOOKINGS', 'services-bookings') . '/api/',
        'query' => [
            'pos'       => null,
            'channel'   => null,
        ]
    ],
    'comments' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_COMMENTS', 'services-comments') . '/api/'
    ],
    'contracts' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_CONTRACTS', 'services-contracts') . '/api/'
    ],
    'logs' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_ACTIVITY_LOG', 'services-activity-log') . '/api/'
    ],
    'event-bus' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_EVENTBUS', 'services-event-bus') . '/api/'
    ],
    'exchange-rates' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_EXCHANGE_RATES', 'services-exchange-rates') . '/api/'
    ],
    'notifications' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_NOTIFICATIONS', 'services-notifications') . '/api/v1/'
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
    ],
    'translations' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_TRANSLATIONS', 'services-translations') . '/api/'
    ]
];
