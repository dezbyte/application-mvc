<?php

namespace Dez\Mvc;

use Colibri\Parameters\ParametersCollection;
use Dez\EventDispatcher\Dispatcher;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\Http\Response;
use Dez\Loader\Loader;
use Dez\Router\Router;
use Dez\Session\Adapter;
use Dez\Template\TemplateInterface;
use Dez\Url\Url;
use Dez\Flash\Adapter as Flash;

/**
 * Class Application
 * @package Dez\Mvc
 *
 * @property Loader loader
 * @property ParametersCollection config
 * @property Dispatcher eventDispatcher
 * @property Dispatcher event
 * @property Request request
 * @property Cookies cookies
 * @property Response response
 * @property Adapter session
 * @property Router router
 * @property Url url
 * @property Flash flash
 * @property TemplateInterface view
 * @property TemplateInterface template
 */
interface InjectableAware
{
  
}