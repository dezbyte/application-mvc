<?php

namespace TestApp;

// composer autoload
use Dez\Config\Config;
use Dez\Mvc\Application;
use Dez\Mvc\FactoryContainer;
use Dez\View\Engine\Php;

error_reporting(1);
ini_set('display_errors', 'On');

include_once '../vendor/autoload.php';

set_exception_handler(function (\Exception $exception) {
    die($exception->getMessage());
});

new FactoryContainer();

$app = new Application();

// bootstrap
$app->config->merge(Config::factory('config/app.php'));
$app->loader->registerNamespaces(
    $app->config['application']['autoload']->toArray()
)->register();

$app->url->setBasePath($app->config['application']['basePath']);

$app->router->add('/:hash/dashboard.html', [
    'controller' => 'index',
    'action' => 'dashboard',
    ['post', 'get']
])->regex('hash', '[0-9]{10,20}');

$app->router->add('/:controller/:action/:params');

$app->view
    ->setViewDirectory($app->config['application']['viewDirectory'])
    ->registerEngine('.php', new Php($app->view));

$app->run();