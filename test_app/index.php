<?php

    namespace TestApp;

    // composer autoload
    use Dez\Mvc\Application;
    use Dez\Mvc\FactoryContainer;
    use Dez\View\Engine\Php;

    error_reporting(1);
    ini_set('display_errors', 1);

    include_once '../vendor/autoload.php';

    new FactoryContainer();

    $app    = new Application();

    $app->view->setViewDirectory( __DIR__ . '/views' )
        ->registerEngine( '.php', new Php( $app->view ) );

    $app->router->add( '/', [
        'controller'    => 'index',
        'acrion'        => 'welcome'
    ] );

    $app->run()->send();
