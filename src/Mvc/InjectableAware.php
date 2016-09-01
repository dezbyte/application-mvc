<?php

namespace Dez\Mvc;

use Dez\Authorizer\Adapter\Session;
use Dez\Authorizer\Adapter\Token;
use Dez\Config\Config;
use Dez\Db\Connection;
use Dez\EventDispatcher\Dispatcher;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\Http\Response;
use Dez\Loader\Loader;
use Dez\Mvc\View\AdapterInterface;
use Dez\Router\Router;
use Dez\Session\Adapter;
use Dez\Url\Url;
use Dez\Flash\Adapter as Flash;

/**
 * Class Application
 * @package Dez\Mvc
 *
 * @property Loader loader
 * @property Config config
 * @property Dispatcher eventDispatcher
 * @property Dispatcher event
 * @property Request request
 * @property Cookies cookies
 * @property Response response
 * @property Adapter session
 * @property Router router
 * @property Url url
 * @property Connection db
 * @property Token|Session auth
 * @property Flash flash
 * @property AdapterInterface view
 * @property AdapterInterface template
 */
interface InjectableAware
{

}