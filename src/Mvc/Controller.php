<?php

namespace Dez\Mvc;

use Dez\Mvc\Controller\Dispatcher;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\Controller\ControllerInterface;
use Dez\DependencyInjection\ContainerInterface;

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

}
