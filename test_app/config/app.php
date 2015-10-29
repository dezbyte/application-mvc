<?php

return [
    'application'   => [

        'staticPath'    => '/_/dez-mvc-app/test_app/',
        'basePath'      => '/_/dez-mvc-app/test_app/',

        'autoload'              => [
            'App\\Controller'     => __DIR__ . '/../controllers',
            'App\\Model'          => __DIR__ . '/../models',
        ],
        'modelDirectory'        => __DIR__ . '/../models',
        'controllerDirectory'   => __DIR__ . '/../controllers',
        'viewDirectory'         => __DIR__ . '/../views',
    ]
];