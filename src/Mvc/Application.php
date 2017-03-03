<?php

namespace Dez\Mvc;

use Colibri\Parameters\ParametersCollection;
use Dez\DependencyInjection\Injectable;
use Dez\DependencyInjection\Service;
use Dez\Http\Response;
use Dez\Mvc\Controller\ControllerResolver;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\Controller\Page404Exception;
use Dez\Router\Router;

class Application extends Injectable implements InjectableAware
{
  
  /**
   * @var string
   */
  protected $controllerNamespace = '\\App\\Controller\\';
  
  /**
   * Application constructor.
   */
  public function __construct()
  {
    $this->setDi(FactoryContainer::instance());
  }
  
  
  /**
   * @return ParametersCollection|\Colibri\Parameters\ParametersInterface
   */
  public static function sampleConfiguration()
  {
    $configurationFilePath = realpath(__DIR__ . '/../sample.configuration.php');
    
    $config = ParametersCollection::createFromFile($configurationFilePath);
    $config['sample_config_file'] = $configurationFilePath;
    
    return $config;
  }
  
  /**
   * @return Response
   * @throws MvcException
   * @throws \Dez\EventDispatcher\Exception
   * @throws \Exception
   */
  public function run()
  {
    $this->event->dispatch(MvcEvent::ON_BEFORE_APP_RUN, new MvcEvent($this));
    
    $router = $this->initializeDefaultRoutes()->handle();
    
    if ($router->isFounded()) {
      
      $resolver = new ControllerResolver($this->getDi());
      
      $namespace = null === $router->getNamespace()
        ? $this->getControllerNamespace()
        : $router->getNamespace();
      
      $resolver->setNamespace($namespace);
      $resolver->setController($router->getController());
      $resolver->setAction($router->getAction());
      $resolver->setParams($router->getMatches());
      
      try {
        $this->prepareView();
        $response = $resolver->execute();
        
        $content = $response->getControllerContent();
        $controller = $response->getControllerInstance();
        
        if (null === $content && $this->response->getBodyFormat() == Response::RESPONSE_HTML) {
          $templatePath = "{$resolver->getController()}/{$resolver->getAction()}";
          $content = $this->render($templatePath, $controller->getPseudoPath());
        }
        
        if (null !== $controller->getLayout()) {
          $content = $this->render($controller->getLayout(), null, [
            'content' => $content
          ]);
        }
        
        $this->response->setContent($content);
        
        $this->event->dispatch(MvcEvent::ON_AFTER_APP_RUN, new MvcEvent($this));
      } catch (Page404Exception $exception) {
        $this->response->setStatusCode(404);
        throw $exception;
      } catch (\Exception $exception) {
        $this->event->dispatch(MvcEvent::ON_DISPATCHER_ERROR, new MvcEvent($this));
        $this->response->setStatusCode(500);
        throw $exception;
      }
      
    } else {
      $this->event->dispatch(MvcEvent::ON_PAGE_404, new MvcEvent($this));
      $this->response->setStatusCode(404);
      
      throw new Page404Exception("Page with route '{$router->getTargetUri()}' was not found");
    }
    
    return $this->response->send();
  }
  
  /**
   * @return Router
   */
  protected function initializeDefaultRoutes()
  {
    $this->router->add('/');
    $this->router->add('/:controller');
    $this->router->add('/:controller/:id');
    $this->router->add('/:controller/:action');
    $this->router->add('/:controller/:action/:id');
    $this->router->add('/:controller/:action/:params');
    
    return $this->router;
  }
  
  /**
   * @return string
   */
  public function getControllerNamespace()
  {
    return $this->controllerNamespace;
  }
  
  /**
   * @param string $controllerNamespace
   * @return static
   */
  public function setControllerNamespace($controllerNamespace)
  {
    $this->controllerNamespace = $controllerNamespace;
    
    return $this;
  }
  
  /**
   * @return $this
   */
  protected function prepareView()
  {
    /** @var Service $service */
    foreach ($this->dependencyInjector as $service) {
      $this->view->set($service->getName(), $this->{$service->getName()});
    }
    
    return $this;
  }
  
  /**
   * @param string $path
   * @param string $pseudoPath
   * @param array $data
   * @return string
   */
  protected function render($path, $pseudoPath, array $data = [])
  {
    $content = $this->view->fetch(null === $pseudoPath ? $path : "$pseudoPath::$path", $data);
    
    return $content;
  }
  
}