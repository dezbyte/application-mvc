<?php

    namespace Dez\Mvc;

    use Dez\DependencyInjection\Container;
    use Dez\Http\Cookies;
    use Dez\Http\Request;
    use Dez\Http\Response;
    use Dez\Router\Router;
    use Dez\Session\Adapter\Files;

    class FactoryContainer extends Container {

        public function __construct() {
            parent::__construct();

            $this->services = [
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
            ];

        }

    }