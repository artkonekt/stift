<?php

return [
    'modules' => [
        Konekt\Client\Providers\ModuleServiceProvider::class => [],
    ],
    'event_listeners' => true,
    'views' => [
        'namespace' => 'stift'
    ],
    'routes' => [
        'prefix'     => 'stift',
        'as'         => 'stift.',
        'middleware' => ['web', 'auth', 'acl'],
        'files'      => ['web']
    ],
    'breadcrumbs' => true
];