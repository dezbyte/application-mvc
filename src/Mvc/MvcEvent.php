<?php

    namespace Dez\Mvc;

    use Dez\EventDispatcher\Event;

    class MvcEvent extends Event {

        protected $target;

        public function __construct( $target ) {
            $this->target  = $target;
        }

        public function getTarget() {
            return $this->target;
        }

    }