<?php

namespace Dez\Mvc\Application;

use Colibri\Parameters\ParametersCollection;
use Colibri\Parameters\ParametersInterface;
use Dez\Mvc\Application;
use Dez\Mvc\Controller\MvcException;
use Dez\Template\Template;

/**
 * Class ConfigurableApplication
 * @package Dez\Mvc\Application
 */
abstract class ConfigurableApplication extends Application
{
  
  /**
   * ConfigurableApplication constructor.
   * @param ParametersInterface $parameters
   */
  public function __construct(ParametersInterface $parameters)
  {
    parent::__construct();
    
    $this->config->merge($parameters);
  }
  
  /**
   * @return $this
   * @throws MvcException
   */
  public function configure()
  {
    /**
     * @var ParametersCollection $serverConfig
     * @var ParametersCollection $applicationConfig
     */
    if ($this->config->offsetExists('server')) {
      $serverConfig = $this->config->get('server');
      
      if ($serverConfig->offsetExists('timezone')) {
        date_default_timezone_set($serverConfig['timezone']);
      }
      
      if ($serverConfig->offsetExists('displayErrors')) {
        ini_set('display_errors', $serverConfig['displayErrors']);
      }
      
      if ($serverConfig->offsetExists('errorLevel')) {
        error_reporting($serverConfig['errorLevel']);
      }
    }
    
    if ($this->config->offsetExists('application')) {
      $applicationConfig = $this->config->get('application');
      
      if ($applicationConfig->offsetExists('autoload')) {
        $this->loader->registerNamespaces($applicationConfig->get('autoload')->toArray())
          ->register();
      }
      
      if ($applicationConfig->offsetExists('controller')) {
        $this->setControllerNamespace($applicationConfig['controller']['namespace']);
      }
      
      if ($applicationConfig->offsetExists('base_path')) {
        $this->url->setBasePath($applicationConfig['base_path']);
        if ($applicationConfig->offsetExists('static_path')) {
          $this->url->setStaticPath($applicationConfig['static_path']);
        }
      }
      
      if ($applicationConfig->offsetExists('view')) {
        $this->dependencyInjector->set('view', function () use ($applicationConfig) {
          $services = iterator_to_array($this->dependencyInjector);
          $directory = $applicationConfig['view']['root_directory'];
          return new Template($directory, $services);
        });
      } else {
        throw new MvcException('Required configuration for view don`ts exists');
      }
    }
    
    return $this;
  }
  
  /**
   * @return $this
   */
  abstract public function initialize();
  
  /**
   * @return $this
   */
  abstract public function injection();
  
}
