<?php

    namespace Dez\Application;

    use Dez\Application\Controller\ControllerException;
    use Dez\Application\Controller\ControllerInterface;
    use Dez\DependencyInjection\ContainerInterface;

    abstract class Controller implements ControllerInterface {

        /**
         * @var ContainerInterface
         */
        static protected $container;

        public function __construct() {

        }

        public function __get( $name ) {

            if( static::$container->has( $name ) ) {
                return static::$container->get( $name );
            } else {
                throw new ControllerException( "Service '{$name}' not found on default container" );
            }

        }

        /**
         * @param ContainerInterface $container
         * @return $this
         */
        public function setDi( ContainerInterface $container ) {
            static::$container      = $container;
            return $this;
        }

        /**
         * @return ContainerInterface
         */
        public function getDi() {
            return static::$container;
        }

    }