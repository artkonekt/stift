<?php

return [
    'modules' => [],
    'event_listeners' => true,
    'views' => [
        'namespace' => 'witser'
    ],
    'routes' => [
        'prefix'     => 'witser',
        'as'         => 'witser.',
        'middleware' => ['web', 'auth', 'acl'],
        'files'      => ['web']
    ],
    'breadcrumbs' => true
];