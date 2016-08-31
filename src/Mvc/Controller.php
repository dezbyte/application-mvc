<?php

namespace Dez\Mvc;

use Dez\Http\Response;
use Dez\Mvc\Controller\Dispatcher;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\Controller\ControllerInterface;
use Dez\DependencyInjection\ContainerInterface;
use Dez\Mvc\UrlRouteQuery\Adapter\OrmQuery;
use Dez\Mvc\UrlRouteQuery\AnonymousMapper;
use Dez\Mvc\UrlRouteQuery\Mapper;
use Dez\ORM\Model\QueryBuilder;
use Dez\Url\Builder;

abstract class Controller implements ControllerInterface
{

    /**
     * @var ContainerInterface
     */
    static protected $container;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $params = [];


    /**
     * Controller constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $name
     * @return mixed
     * @throws MvcException
     */
    public function __get($name)
    {
        if (static::$container->has($name)) {
            return static::$container->get($name);
        } else {
            throw new MvcException("Service '{$name}' not found on default container");
        }
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setDi(ContainerInterface $container)
    {
        static::$container = $container;
        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getDi()
    {
        return static::$container;
    }

    /**
     * @param array $parameters
     * @param bool $render
     * @return mixed
     * @throws MvcException
     */
    public function execute(array $parameters = [], $render = false)
    {
        if(! isset($parameters['action'])) {
            throw new MvcException("Action required for forwarding");
        }

        $dispatcher = new Dispatcher($this->getDi());

        $dispatcher->setNamespace(isset($parameters['namespace']) ? $parameters['namespace'] : $this->getNamespace());
        $dispatcher->setController(isset($parameters['controller']) ? $parameters['controller'] : $this->getName());

        if(isset($parameters['params'], $parameters['params'][0])) {
            $dispatcher->setParams($parameters['params']);
        }

        $dispatcher->setAction($parameters['action']);

        $content = $dispatcher->dispatch();

        if($render === true) {
            $content = $this->view->fetch("{$dispatcher->getController()}/{$dispatcher->getAction()}");
        }

        return $content;
    }

    /**
     * @param string $relative
     * @return Response
     */
    public function redirect($relative)
    {
        return $this->response->redirect($this->url->path($relative))->setStatusCode(302);
    }

    /**
     * @return Response
     */
    public function refresh()
    {
        return $this->response->redirect($this->router->getTargetUri())->setStatusCode(302);
    }

    /**
     * @param Mapper $mapper
     * @param QueryBuilder $queryBuilder
     * @return AnonymousMapper
     * @throws UrlRouteQuery\MapperException
     */
    public function grid(Mapper $mapper, QueryBuilder $queryBuilder)
    {
        $source = new OrmQuery($queryBuilder);

        $mapper->setDataSource($source);
        $mapper->setDi($this->getDi());

        $mapper->processRequestParams();

        $builder = new Builder("{$this->getName()}:{$this->getAction()}", $this->getParams(), $this->router);
        $mapper->path($builder->make());

        return $mapper;
    }

    /**
     * @return null
     */
    public function beforeExecute()
    {

    }

    /**
     * @return null
     */
    public function afterExecute()
    {

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return static
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return static
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return static
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

}
