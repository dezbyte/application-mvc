<?php

    namespace Dez\Mvc;

    use Dez\DependencyInjection\Injectable;
    use Dez\Auth\Auth;
    use Dez\Config\Config;
    use Dez\Db\Connection;
    use Dez\EventDispatcher\Dispatcher;
    use Dez\Http\Cookies;
    use Dez\Http\Request;
    use Dez\Http\Response;
    use Dez\Loader\Loader;
    use Dez\Mvc\Controller\ControllerException;
    use Dez\Router\Router;
    use Dez\Session\Adapter;
    use Dez\View\View;

    use Dez\Mvc\Controller\Dispatcher as ControllerDispatcher;


    /**
     * Class Application
     * @package Dez\Mvc
     *
     * @property Loader loader
     * @property Config config
     * @property Dispatcher eventDispatcher
     * @property Dispatcher event
     * @property Request request
     * @property Cookies cookies
     * @property Response response
     * @property Adapter session
     * @property Router router
     * @property View view
     * @property Connection db
     * @property Auth auth
     */

    class Application extends Injectable {

        public function __construct()
        {
            $this->setDi( FactoryContainer::instance() );
        }

        public function run()
        {
            $this->event->dispatch( 'beforeApplicationRun', new MvcEvent( $this ) );

            $router     = $this->router;

            $router->handle();

            if( ! $router->isFounded() ) {
                throw new ControllerException( 'Route not found' );
            }

            $dispatcher = new ControllerDispatcher( $this->getDi() );

            $dispatcher->setName( $router->getController() );
            $dispatcher->setAction( $router->getAction() );
            $dispatcher->setParams( $router->getMatches() );

            $dispatcher->dispatch();

            $this->event->dispatch( 'afterApplicationRun', new MvcEvent( $this ) );
            return $this->response;
        }



    }