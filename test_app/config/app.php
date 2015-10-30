<?php

return [
    'application'   => [

        'staticPath'    => '/',
        'basePath'      => '/',

        'autoload'              => [
            'App\\Controller'     => __DIR__ . '/../controllers',
            'App\\Model'          => __DIR__ . '/../models',
        ],
        'modelDirectory'        => __DIR__ . '/../models',
        'controllerDirectory'   => __DIR__ . '/../controllers',
        'viewDirectory'         => __DIR__ . '/../views',
    ]
];