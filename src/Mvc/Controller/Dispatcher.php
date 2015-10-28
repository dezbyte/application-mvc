<?php

    namespace Dez\Mvc\Controller;

    use Dez\DependencyInjection\ContainerInterface;
    use Dez\Mvc\Controller;

    class Dispatcher {

        protected $controller;

        protected $name;

        protected $action;

        protected $params   = [];

        static protected $di;

        public function __construct( ContainerInterface $di ) {
            static::$di     = $di;
        }

        /**
         * @return Controller
         */
        public function getController() {
            return $this->controller;
        }

        /**
         * @param mixed $controller
         * @return static
         */
        public function setController( Controller $controller) {
            $this->controller = $controller;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param mixed $name
         * @return static
         */
        public function setName( $name ) {
            $this->name = $name;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getAction() {
            return $this->action;
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

        public function dispatch() {

            $controllerName     = ucfirst( strtolower( $this->getName() ) ) . 'Controller';
            $actionName         = ucfirst( strtolower( $this->getAction() ) ) . 'Action';

        }

    }