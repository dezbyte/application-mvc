<?php

    namespace Dez\Application\Controller;

    use Dez\Auth\Auth;
    use Dez\Config\Config;
    use Dez\Db\Connection;
    use Dez\DependencyInjection\InjectableInterface;
    use Dez\EventDispatcher\Dispatcher;
    use Dez\Http\Cookies;
    use Dez\Http\Request;
    use Dez\Http\Response;
    use Dez\Loader\Loader;
    use Dez\Router\Router;
    use Dez\Session\Adapter;
    use Dez\View\View;

    /**
     * @property Loader loader
     * @property Config config
     * @property Dispatcher eventDispatcher
     * @property Dispatcher event
     * @property Request request
     * @property Cookies cookies
     * @property Response response
     * @property Adapter session
     * @property Router router
     * @property View view
     * @property Connection db
     * @property Auth auth
     */

    interface ControllerInterface extends InjectableInterface {



    }