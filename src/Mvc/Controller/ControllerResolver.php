<?php

namespace Dez\Mvc\Controller;

use Dez\DependencyInjection\ContainerInterface;
use Dez\Mvc\Controller;
use Dez\Mvc\MvcEvent;

class ControllerResolver
{

    /**
     * @var ContainerInterface|null
     */
    protected $container = null;

    /**
     * @var ControllerResponse|null
     */
    protected $response = null;

    /**
     * @var string|null
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * ControllerResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = new ControllerResponse();
    }

    public function execute()
    {

        $class = $this->getControllerClass();

        try {
            $reflectionClass = new \ReflectionClass($class);

            if($reflectionClass->implementsInterface(ControllerInterface::class)) {
                $controller = $reflectionClass->newInstance();
                $this->getResponse()->setControllerInstance($controller);
            }

die(var_dump($this));

        } catch (\ReflectionException $exception) {
            $this->container->get('event')->dispatch(MvcEvent::ON_PAGE_404, new MvcEvent($this));
            throw new Page404Exception($exception->getMessage());
        } catch (\Exception $exception) {
            throw new RuntimeMvcException($exception->getMessage());
        }

        $controller = new $class();

        $action = $this->getActionCamelize();
        if (!method_exists($controller, $action)) {
            $this->container->get('event')->dispatch(MvcEvent::ON_PAGE_404, new MvcEvent($this));
            throw new Page404Exception("Method '{$action}' in controller '{$controllerClass}' not found");
        }

        static::$di->get('event')->dispatch(MvcEvent::ON_BEFORE_RUN, new MvcEvent($controller));

        try {
            $controller->setDi(static::$di);

            $controller->setNamespace($this->getNamespace());
            $controller->setName($this->getController());
            $controller->setAction($this->getAction());
            $controller->setParams($this->getParams());

            $controller->beforeExecute();
            $controllerOutput = call_user_func_array([$controller, $action], $this->getParams());
            $controller->afterExecute();

            static::$di->get('event')->dispatch(MvcEvent::ON_AFTER_RUN, new MvcEvent($controller));
        } catch (\Exception $exception) {
            static::$di->get('event')->dispatch(MvcEvent::ON_ACTION_ERROR, new MvcEvent($exception));
            throw new MvcException($exception->getMessage());
        }

    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return trim($this->getNamespace(), '\\') .'\\'. $this->getControllerCamelize();
    }

    /**
     * @return ControllerResponse|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ControllerResponse|null $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param null|string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getControllerCamelize()
    {
        $parts = preg_split('/[-_]+/uis', $this->getController());

        return implode('', array_map('ucfirst', $parts)) . 'Controller';
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getActionCamelize()
    {
        $parts = preg_split('/[-_]+/uis', $this->getAction());

        return lcfirst(implode('', array_map('ucfirst', $parts))) . 'Action';
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
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
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

}