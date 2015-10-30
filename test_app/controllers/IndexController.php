<?php

    namespace App\Controller;

    use Dez\Mvc\Controller;

    class IndexController extends Controller
    {

        public function indexAction( $id )
        {
            $this->view->set( 'id', 321 );
        }

        public function helloAction ()
        {

        }

    }