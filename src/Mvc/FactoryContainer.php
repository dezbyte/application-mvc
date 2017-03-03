<?php

namespace Dez\Mvc;

use Colibri\Parameters\ParametersCollection;
use Dez\DependencyInjection\Container;
use Dez\DependencyInjection\Service;
use Dez\EventDispatcher\Dispatcher;
use Dez\Flash\Flash\Session as FlashSession;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\Http\Response;
use Dez\Loader\Loader;
use Dez\Router\Router;
use Dez\Session\Adapter\Files;
use Dez\Template\NullTemplate;
use Dez\Url\Url;

class FactoryContainer extends Container
{
  
  public function __construct()
  {
    parent::__construct();
    
    $this->services = [
      'loader' => new Service('loader', new Loader()),
      'config' => new Service('config', new ParametersCollection()),
      'event' => new Service('event', new Dispatcher()),
      'request' => new Service('request', new Request()),
      'response' => new Service('response', new Response()),
      'cookies' => new Service('cookies', new Cookies()),
      'router' => new Service('router', new Router()),
      'session' => new Service('session', new Files()),
      'url' => new Service('url', new Url()),
      'flash' => new Service('flash', new FlashSession()),
      'view' => new Service('view', new NullTemplate()),
    ];
    
    // Make alias
    $this->services['eventDispatcher'] = $this->services['event'];
    $this->services['template'] = $this->services['view'];
  }
  
}