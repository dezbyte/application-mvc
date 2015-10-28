<?php

    namespace Dez\Mvc;

    use Dez\Config\Config;
    use Dez\DependencyInjection\Container;
    use Dez\DependencyInjection\Service;
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
                'loader'        => new Service( 'loader', new Loader() ),
                'config'        => new Service( 'config', new Config( [] ) ),
                'event'         => new Service( 'event', new Dispatcher() ),
                'request'       => new Service( 'request', new Request() ),
                'response'      => new Service( 'response', new Response() ),
                'cookies'       => new Service( 'cookies', new Cookies() ),
                'router'        => new Service( 'router', new Router() ),
                'session'       => new Service( 'session', new Files() ),
                'view'          => new Service( 'view', new View() ),
            ];

            $this->services['eventDispatcher']  = $this->services['event'];

        }

    }