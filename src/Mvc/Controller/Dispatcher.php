<?php

namespace Dez\Mvc\Controller;

use Dez\DependencyInjection\Container;
use Dez\DependencyInjection\ContainerInterface;
use Dez\Mvc\Controller;
use Dez\Mvc\MvcEvent;

class Dispatcher
{

    /**l
     * @var Container
     */
    static protected $di;
    protected $namespace;
    protected $controller;
    protected $action;
    protected $params = [];

    public function __construct(ContainerInterface $di)
    {
        static::$di = $di;
    }

    /**
     * @return mixed
     * @throws MvcException
     */
    public function dispatch()
    {

        $controller = null;
        $controllerOutput = null;
        $controllerClass = $this->getNamespace() . $this->getControllerCamelize();

        if (!class_exists($controllerClass)) {
            throw new MvcException("Controller class '{$controllerClass}' not found");
        }

        /** @var Controller $controller */
        $controller = new $controllerClass();
        $action = $this->getActionCamelize();

        if (!method_exists($controller, $action)) {
            throw new MvcException("Method '{$action}' in controller '{$controllerClass}' not found");
        }

        static::$di->get('event')->dispatch(MvcEvent::ON_BEFORE_RUN, new MvcEvent($controller));

        try {
            $controller->setDi(static::$di);
            $controller->setNamespace($this->getNamespace());
            $controller->setName($this->getController());
            $controller->setAction($this->getAction());

            $controller->beforeExecute();
            $controllerOutput = call_user_func_array([$controller, $action], $this->getParams());
            $controller->afterExecute();

            static::$di->get('event')->dispatch(MvcEvent::ON_AFTER_RUN, new MvcEvent($controller));
        } catch (\Exception $exception) {
            static::$di->get('event')->dispatch(MvcEvent::ON_ACTION_ERROR, new MvcEvent($exception));
            throw new MvcException($exception->getMessage());
        }

        return $controllerOutput;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
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
    public function getControllerCamelize()
    {
        return implode('', array_map('ucfirst', explode('-', $this->getController()))) . 'Controller';
    }

    /**
     * @return string
     */
    public function getController()
    {
        return strtolower($this->controller);
    }

    /**
     * @param string $controller
     * @return static
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionCamelize()
    {
        return lcfirst(implode('', array_map('ucfirst', explode('-', $this->getAction())))) . 'Action';
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return strtolower($this->action);
    }

    /**
     * @param mixed $action
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