<?php

    namespace Dez\Mvc;

    use Dez\DependencyInjection\Injectable;
    use Dez\Auth\Auth;
    use Dez\Config\Config;
    use Dez\Db\Connection;
    use Dez\DependencyInjection\Service;
    use Dez\EventDispatcher\Dispatcher;
    use Dez\Http\Cookies;
    use Dez\Http\Request;
    use Dez\Http\Response;
    use Dez\Loader\Loader;
    use Dez\Mvc\Controller\MvcException;
    use Dez\Router\Router;
    use Dez\Session\Adapter;
    use Dez\Url\Url;
    use Dez\View\View;
    use Dez\Flash\Adapter as Flash;

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
     * @property Url url
     * @property View view
     * @property Connection db
     * @property Auth auth
     * @property Flash flash
     */

    class Application extends Injectable {

        protected $page404Handler;

        protected $errorHandler;

        protected $controllerNamespace  = '\\App\\Controller\\';

        public function __construct()
        {
            $this->setDi( FactoryContainer::instance() );
        }

        /**
         * @return Response
         * @throws MvcException
         * @throws \Dez\EventDispatcher\Exception
         * @throws \Exception
         */
        public function run()
        {
            $this->event->dispatch( 'beforeApplicationRun', new MvcEvent( $this ) );

            $router     = $this->initializeDefaultRoutes()->handle();

            if( $router->isFounded() ) {

                $dispatcher = new ControllerDispatcher( $this->getDi() );

                $dispatcher->setNamespace( $this->getControllerNamespace() );
                $dispatcher->setController( $router->getController() );
                $dispatcher->setAction( $router->getAction() );
                $dispatcher->setParams( $router->getMatches() );

                try {

                    $content = $dispatcher->dispatch();

                    if($this->response->getBodyFormat() == Response::RESPONSE_HTML) {
                        if($content === null) {
                            $this->view->addLayout( "layouts/{$router->getController()}" );
                            $content    = $this->render( "{$router->getController()}/{$router->getAction()}" );
                        }
                        $this->response->setContent( $content );
                    }

                    $this->event->dispatch( 'afterApplicationRun', new MvcEvent( $this ) );
                } catch ( \Exception $exception ) {
                    $this->response->setStatusCode( 500 );

                    if( $this->getErrorHandler() instanceof \Closure ) {
                        call_user_func_array( $this->getErrorHandler(), [ $exception, $this ] );
                        $this->response->setContent( $this->render( 'internal_error.php' ) );
                    } else {
                        throw $exception;
                    }
                }

            } else {
                $this->event->dispatch( 'onPageNotFound', new MvcEvent( $this ) );
                $this->response->setStatusCode( 404 );

                if( $this->getPage404Handler() instanceof \Closure ) {
                    call_user_func_array( $this->getPage404Handler(), [ $this ] );
                    $this->response->setContent( $this->render( 'error_404.php' ) );
                } else {
                    throw new MvcException( "Page {$router->getTargetUri()} not found" );
                }
            }

            return $this->response->sendContent();
        }

        /**
         * @return $this
         */
        protected function prepareView()
        {
            /** @var Service $service */
            foreach( $this->dependencyInjector as $service ) {
                $this->view->set( $service->getName(), $this->{ $service->getName() } );
            }
            return $this;
        }

        /**
         * @return Router
         */
        protected function initializeDefaultRoutes()
        {
            $this->router->add( '/' );
            $this->router->add( '/:controller' );
            $this->router->add( '/:controller/:action' );
            $this->router->add( '/:controller/:action/:id' );
            return $this->router;
        }

        /**
         * @param $path
         * @return $this
         * @throws \Dez\Http\Exception
         * @throws \Exception
         */
        protected function render( $path )
        {
            $this->response->sendCookies()->sendHeaders();
            $content    = null;
            if( $this->response->isEnableBody() ) {
                $this->prepareView();
                $content    = $this->view->render( $path );
            }
            return $content;
        }

        /**
         * @return \Closure
         */
        public function getPage404Handler()
        {
            return $this->page404Handler;
        }

        /**
         * @param \Closure $page404Handler
         * @return $this
         */
        public function setPage404Handler( \Closure $page404Handler )
        {
            $this->page404Handler = \Closure::bind( $page404Handler, $this );
            return $this;
        }

        /**
         * @return \Closure
         */
        public function getErrorHandler()
        {
            return $this->errorHandler;
        }

        /**
         * @param mixed $errorHandler
         * @return \Closure
         */
        public function setErrorHandler( \Closure $errorHandler )
        {
            $this->errorHandler = \Closure::bind( $errorHandler, $this );
            return $this;
        }

        /**
         * @return string
         */
        public function getControllerNamespace()
        {
            return $this->controllerNamespace;
        }

        /**
         * @param string $controllerNamespace
         * @return static
         */
        public function setControllerNamespace($controllerNamespace)
        {
            $this->controllerNamespace = $controllerNamespace;
            return $this;
        }

    }