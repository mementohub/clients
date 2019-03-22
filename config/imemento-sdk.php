<?php

return [
    'comments' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_COMMENTS', 'services-comments') . '/api/'
    ],
    'contracts' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_CONTRACTS', 'services-contracts') . '/api/'
    ],
    'roles' => [
        'base_uri'  => env('ENDPOINT_INTERNAL_SERVICES_ROLES', 'services-roles') . '/api/'
    ],
    'profiles' => [
        'base_uri' => env('ENDPOINT_INTERNAL_SERVICES_PROFILES', 'services-profiles') . '/api/'
    ]
];
