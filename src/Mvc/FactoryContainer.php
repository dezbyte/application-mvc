<?php

    namespace Dez\Mvc;

    use Dez\Config\Config;
    use Dez\DependencyInjection\Container;
    use Dez\EventDispatcher\Dispatcher;
    use Dez\Http\Cookies;
    use Dez\Http\Request;
    use Dez\Http\Response;
    use Dez\Loader\Loader;
    use Dez\Router\Router;
    use Dez\Session\Adapter\Files;
    use Dez\View\View;

    class FactoryContainer extends Container {

        public function __construct() {
            parent::__construct();

            $this->services = [
                'loader'    => function() {
                    return new Loader();
                },
                'config'    => function() {
                    return new Config( [] );
                },
                'event'    => function() {
                    return new Dispatcher();
                },
                'request'   => function() {
                    return new Request();
                },
                'response'  => function() {
                    return new Response();
                },
                'cookies'  => function() {
                    return new Cookies();
                },
                'router'  => function() {
                    return new Router();
                },
                'session'   => function() {
                    return new Files();
                },
                'view'   => function() {
                    return new View();
                },
            ];

        }

    }