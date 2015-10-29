<?php

    namespace TestApp;

    // composer autoload
    use Dez\Config\Config;
    use Dez\Mvc\Application;
    use Dez\Mvc\FactoryContainer;
    use Dez\View\Engine\Php;

    error_reporting(1);
    ini_set('display_errors', 1);

    include_once '../vendor/autoload.php';

    new FactoryContainer();

    $app    = new Application();

    // bootstrap
    $app->config->merge( Config::fatory( 'config/app.php' ) );

    $app->loader->registerNamespaces(
        $app->config['application']['autoload']->toArray()
    )->register();

    $app->view
        ->setViewDirectory( $app->config['application']['viewDirectory'] )
        ->registerEngine( '.php', new Php( $app->view ) );

    $response   = $app->run();

    $response->send();
