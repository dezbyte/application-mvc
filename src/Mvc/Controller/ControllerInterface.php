<?php

namespace Dez\Mvc\Controller;

use Dez\DependencyInjection\ContainerInterface;
use Dez\DependencyInjection\InjectableInterface;
use Dez\Mvc\InjectableAware;
use Dez\Mvc\UrlRouteQuery\Mapper;
use Dez\ORM\Model\QueryBuilder;

interface ControllerInterface extends InjectableInterface, InjectableAware
{

    /**
     * @return mixed
     */
    public function beforeExecute();

    /**
     * @return mixed
     */
    public function afterExecute();

    /**
     * @param $relative
     * @return mixed
     */
    public function redirect($relative);

    /**
     * @return mixed
     */
    public function refresh();

    /**
     * @param Mapper $mapper
     * @param QueryBuilder $queryBuilder
     * @return mixed
     */
    public function injectMapper(Mapper $mapper, QueryBuilder $queryBuilder);

    /**
     * @return string
     */
    public function getName();
    /**
     * @param string $name
     * @return static
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getNamespace();
    /**
     * @param string $namespace
     * @return static
     */
    public function setNamespace($namespace);

    /**
     * @return string
     */
    public function getAction();

    /**
     * @param string $action
     * @return static
     */
    public function setAction($action);

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param array $params
     * @return static
     */
    public function setParams($params);

    /**
     * @return null|string
     */
    public function getLayout();

    /**
     * @param null|string $layout
     */
    public function setLayout($layout);

    /**
     * @return null|string
     */
    public function getPseudoPath();

    /**
     * @param null|string $pseudoPath
     * @return $this
     */
    public function setPseudoPath($pseudoPath);

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass();
    /**
     * @param \ReflectionClass $reflectionClass
     * @return $this
     */
    public function setReflectionClass($reflectionClass);

    /**
     * @return \ReflectionMethod
     */
    public function getReflectionAction();

    /**
     * @param \ReflectionMethod $reflectionAction
     * @return $this
     */
    public function setReflectionAction($reflectionAction);

    /**
     * @return ContainerInterface
     */
    public static function getContainer();

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public static function setContainer($container);

}