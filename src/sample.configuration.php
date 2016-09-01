<?php

return [
    'application' => [
        'static_path' => '/static/',
        'base_path' => '/',
        'autoload' => [
            'MySite\\Controllers' => '/var/www/site/app/controllers',
        ],
        'model' => [
            'root_directory' => '/var/www/site/app/models'
        ],
        'controller' => [
            'root_directory' => '/var/www/site/app/controllers',
            'namespace' => 'MySite\\Controllers\\',
        ],
        'view' => [
            'root_directory' => '/var/www/site/app/templates',
        ],
    ],
    'db' => [
        'connection_name' => 'development',
        'connection' => [
            'development' => [
                'dsn' => 'mysql:host=localhost;dbname=my-site',
                'user' => 'root',
                'password' => '0000',
            ],
            'production' => [
                'dsn' => 'mysql:host=localhost;dbname=my-site',
                'user' => 'root',
                'password' => '0000',
            ],
        ],
    ],
    'server' => [
        'timezone' => 'Europe/Kiev',
        'displayErrors' => 'On',
        'errorLevel' => '1',
    ],
];