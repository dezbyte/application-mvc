<?php

    namespace App\Controller;

    use Dez\Http\Response;
    use Dez\Mvc\Controller;
    use Dez\Mvc\GridRouteMapper\Mapper;

    class IndexController extends Controller
    {

        public function indexAction( $id )
        {
            
//            $this->grid(Users::query());

            $mapper = new TestMapper();
            $mapper->setDi($this->getDi());
            $mapper->setAllowedFilter(['id', 'email', 'name']);
            $mapper->setAllowedOrder(['id', 'views']);
            $mapper->processRequestParams();

            $mapper->addFilter('status', Mapper::MAPPER_ENUM, 'publisher');
            $mapper->addFilter('email', Mapper::MAPPER_LIKE, 'gmail');

            $mapper->addFilter('id', Mapper::MAPPER_GREATER_THAN_EQUAL, 3);
            $mapper->addFilter('id', Mapper::MAPPER_LESS_THAN, 45);

            $mapper->setOrder('views', Mapper::MAPPER_ORDER_DESC);

            $mapper->setPrefixUrl('users/list');

            die(var_dump($mapper->getUrl()));

            $this->view->set('content', $this->execute([
                'action' => 'test1',
            ], true));
        }

        public function test1Action()
        {
            $this->view->set('content', __METHOD__);
            $this->view->set('sub_content', $this->execute([
                'action' => 'test2',
                'params' => [
                    __METHOD__
                ]
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