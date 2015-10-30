<?php

    namespace Dez\Mvc\Controller;

    use Dez\DependencyInjection\Container;
    use Dez\DependencyInjection\ContainerInterface;
    use Dez\Mvc\Controller;
    use Dez\Mvc\MvcEvent;

    class Dispatcher {

        protected $namespace;

        protected $controller;

        protected $action;

        protected $params   = [];

        /**
         * @var Container
         */
        static protected $di;

        public function __construct( ContainerInterface $di ) {
            static::$di     = $di;
        }

        /**
         * @return Controller|null
         * @throws MvcException
         * @throws \Exception
         */
        public function dispatch() {

            $controller         = null;
            $controllerClass    = $this->getNamespace() . $this->getController();

            if( class_exists( $controllerClass ) ) {
                /** @var Controller $controller */
                $controller     = new $controllerClass();
                $action         = $this->getAction();

                if( method_exists( $controller, $action ) ) {
                    static::$di->get( 'event' )->dispatch( 'beforeActionRun', new MvcEvent( $controller ) );

                    try {
                        $controller->setDi( static::$di );
                        call_user_func_array( [ $controller, $action ], $this->getParams() );
                        static::$di->get( 'event' )->dispatch( 'afterActionRun', new MvcEvent( $controller ) );
                    } catch ( \Exception $exception ) {
                        static::$di->get( 'event' )->dispatch( 'onActionRuntimeError', new MvcEvent( $exception ) );
                        throw $exception;
                    }
                } else {
                    throw new MvcException( "Method '{$action}' in controller '{$controllerClass}' not found" );
                }
            } else {
                throw new MvcException( "Controller class '{$controllerClass}' not found" );
            }

            return $controller;
        }

        /**
         * @return mixed
         */
        public function getNamespace() {
            return $this->namespace;
        }

        /**
         * @param mixed $namespace
         * @return static
         */
        public function setNamespace( $namespace ) {
            $this->namespace = $namespace;
            return $this;
        }

        /**
         * @return string
         */
        public function getController() {
            return ucfirst( strtolower( $this->controller ) ) . 'Controller';
        }

        /**
         * @param string $controller
         * @return static
         */
        public function setController( $controller) {
            $this->controller = $controller;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getAction() {
            return strtolower( $this->action ) . 'Action';
        }

        /**
         * @param mixed $action
         * @return static
         */
        public function setAction( $action ) {
            $this->action = $action;
            return $this;
        }

        /**
         * @return array
         */
        public function getParams() {
            return $this->params;
        }

        /**
         * @param array $params
         * @return static
         */
        public function setParams( $params ) {
            $this->params = $params;
            return $this;
        }

    }