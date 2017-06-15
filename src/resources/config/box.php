<?php

return [
    'modules' => [],
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