<?php

    namespace App\Controller;

    use Dez\Http\Response;
    use Dez\Mvc\Controller;

    class IndexController extends Controller
    {

        public function indexAction( $id )
        {
//            $this->response->setBodyFormat(Response::RESPONSE_API_JSON);
            $this->view->set('controller', $this);

            $this->forward([
                'action' => 'hello'
            ]);
        }

        public function helloAction ()
        {
            $this->view->set('controller', $this);
            $this->response->setContent([1]);
        }

    }