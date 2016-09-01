<?php

    namespace App\Controller;

    use Dez\Http\Response;
    use Dez\Mvc\Controller;
    use Dez\Mvc\UrlRouteQuery\AnonymousMapper;

    class IndexController extends Controller
    {

        public function indexAction( $id )
        {
            
//            $this->grid(Users::query());

            $mapper = new AnonymousMapper();
            $mapper->setDi($this->getDi());

            $mapper->setAllowedFilter(['id', 'email', 'name', 'salary', 'size', 'vendor', 'price', 'currency']);
            $mapper->setAllowedOrder(['id', 'views']);

            $mapper->processRequestParams();

            $this->view->set('mapper', $mapper->path('index/index'));

//            $this->view->set('content', $this->execute([
//                'action' => 'test1',
//            ], true));
        }

        public function test1Action()
        {
            $this->view->set('content', __METHOD__);
            $this->view->set('sub_content', $this->execute([
                'action' => 'test2',
                'params' => [__METHOD__]
            ]));
        }

        public function test2Action()
        {
            return __METHOD__;
        }

        public function helloAction ()
        {
            $this->view->set('controller', $this->execute([
                'action' => 'test2'
            ]));
            $this->response->setContent([1]);
        }

    }