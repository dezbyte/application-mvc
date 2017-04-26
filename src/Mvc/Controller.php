<?php

namespace Dez\Mvc;

use Dez\Http\Response;
use Dez\Mvc\Controller\ControllerResolver;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\Controller\ControllerInterface;
use Dez\DependencyInjection\ContainerInterface;
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
   * @var null|string
   */
  protected $layout = null;
  
  /**
   * @var null|string
   */
  protected $pseudoPath = null;
  
  /**
   * @var \ReflectionClass
   */
  protected $reflectionClass = null;
  
  /**
   * @var \ReflectionMethod
   */
  protected $reflectionAction = null;
  
  
  /**
   * Controller constructor.
   */
  public function __construct()
  {
    
  }
  
  /**
   * @return ContainerInterface
   */
  public static function getContainer()
  {
    return static::$container;
  }
  
  /**
   * @param ContainerInterface $container
   * @return $this
   */
  public static function setContainer($container)
  {
    self::$container = $container;
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
   * @param array $parameters
   * @param bool $render
   * @return mixed
   * @throws MvcException
   */
  public function execute(array $parameters = [], $render = false)
  {
    if (!isset($parameters['action'])) {
      throw new MvcException("Action required for forwarding");
    }
    
    $resolver = new ControllerResolver($this->getDi());
    
    $resolver->setNamespace(isset($parameters['namespace']) ? $parameters['namespace'] : $this->getNamespace());
    $resolver->setController(isset($parameters['controller']) ? $parameters['controller'] : $this->getName());
    
    if (isset($parameters['params'], $parameters['params'][0])) {
      $resolver->setParams($parameters['params']);
    }
    
    $resolver->setAction($parameters['action']);
    
    $response = $resolver->execute();
    $content = $response->getControllerContent();
    
    if ($render === true && $this->response->isEnableBody() && $this->response->getBodyFormat() === Response::RESPONSE_HTML) {
      $content = $this->view->render("{$resolver->getController()}/{$resolver->getAction()}");
    }
    
    return $content;
  }
  
  /**
   * @return ContainerInterface
   */
  public function getDi()
  {
    return static::$container;
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
   * @return null|string
   */
  public function getLayout()
  {
    return $this->layout;
  }
  
  /**
   * @param null|string $layout
   */
  public function setLayout($layout)
  {
    $this->layout = $layout;
  }
  
  /**
   * @return null|string
   */
  public function getPseudoPath()
  {
    return $this->pseudoPath;
  }
  
  /**
   * @param null|string $pseudoPath
   * @return $this
   */
  public function setPseudoPath($pseudoPath)
  {
    $this->pseudoPath = $pseudoPath;
    return $this;
  }
  
  /**
   * @return \ReflectionClass
   */
  public function getReflectionClass()
  {
    return $this->reflectionClass;
  }
  
  /**
   * @param \ReflectionClass $reflectionClass
   * @return $this
   */
  public function setReflectionClass($reflectionClass)
  {
    $this->reflectionClass = $reflectionClass;
    return $this;
  }
  
  /**
   * @return \ReflectionMethod
   */
  public function getReflectionAction()
  {
    return $this->reflectionAction;
  }
  
  /**
   * @param \ReflectionMethod $reflectionAction
   * @return $this
   */
  public function setReflectionAction($reflectionAction)
  {
    $this->reflectionAction = $reflectionAction;
    return $this;
  }
  
}
