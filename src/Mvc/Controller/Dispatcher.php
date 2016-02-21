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
         * @return mixed
         * @throws MvcException
         * @throws \Exception
         */
        public function dispatch() {

            $controller         = null;
            $controllerOutput   = null;
            $controllerClass    = $this->getNamespace() . $this->getController();

            if( class_exists( $controllerClass ) ) {
                /** @var Controller $controller */
                $controller     = new $controllerClass();
                $action         = $this->getAction();

                if( method_exists( $controller, $action ) ) {
                    static::$di->get( 'event' )->dispatch( 'beforeActionRun', new MvcEvent( $controller ) );

                    try {
                        $controller->setDi( static::$di );

                        $controller->beforeExecute();
                        $controllerOutput = call_user_func_array( [ $controller, $action ], $this->getParams() );
                        $controller->afterExecute();

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

            return $controllerOutput;
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
            $controller     = strtolower( $this->controller );
            return implode( '', array_map( 'ucfirst', explode( '-', $controller ) ) ) . 'Controller';
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
            $action     = strtolower( $this->action );
            return lcfirst( implode( '', array_map( 'ucfirst', explode( '-', $action ) ) ) ) . 'Action';
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