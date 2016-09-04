<?php

return [
    'application' => [
        'static_path' => '/_/dez-mvc-app/test_app/',
        'base_path' => '/dez-mvc-app/test-app/',
        'autoload' => [
            'App\\Controller' => __DIR__ . '/../controllers',
        ],
        'model' => [
            'root_directory' => '/var/www/site/app/models'
        ],
        'controller' => [
//            'root_directory' => '/var/www/site/app/controllers',
            'namespace' => 'App\\Controller\\',
        ],
        'view' => [
            'root_directory' => __DIR__ . '/../views',
        ],
    ],
    'db' => [
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
        'errorLevel' => E_ALL,
    ],
];