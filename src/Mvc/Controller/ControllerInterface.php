<?php

    namespace Dez\Mvc\Controller;

    use Dez\Auth\Auth;
    use Dez\Config\Config;
    use Dez\Db\Connection;
    use Dez\DependencyInjection\InjectableInterface;
    use Dez\EventDispatcher\Dispatcher as EventDispatcher;
    use Dez\Http\Cookies;
    use Dez\Http\Request;
    use Dez\Http\Response;
    use Dez\Loader\Loader;
    use Dez\Router\Router;
    use Dez\Session\Adapter;
    use Dez\Url\Url;
    use Dez\View\View;
    use Dez\Flash\Adapter as Flash;

    /**
     * @property Loader loader
     * @property Config config
     * @property EventDispatcher eventDispatcher
     * @property EventDispatcher event
     * @property Request request
     * @property Cookies cookies
     * @property Response response
     * @property Adapter session
     * @property Router router
     * @property View view
     * @property Connection db
     * @property Auth auth
     * @property Url url
     * @property Flash flash
     */

    interface ControllerInterface extends InjectableInterface {

        public function beforeExecute();

        public function afterExecute();

        public function execute();

    }