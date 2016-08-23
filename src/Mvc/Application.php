<?php

namespace Dez\Mvc;

use Dez\Authorizer\Adapter\Session;
use Dez\Authorizer\Adapter\Token;
use Dez\Config\Config;
use Dez\Db\Connection;
use Dez\DependencyInjection\Injectable;
use Dez\DependencyInjection\Service;
use Dez\EventDispatcher\Dispatcher;
use Dez\Flash\Adapter as Flash;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\Http\Response;
use Dez\Loader\Loader;
use Dez\Mvc\Controller\Dispatcher as ControllerDispatcher;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\Controller\Page404Exception;
use Dez\Router\Router;
use Dez\Session\Adapter;
use Dez\Url\Url;
use Dez\View\View;


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
 * @property View view
 * @property Connection db
 * @property Token|Session auth
 * @property Flash flash
 */
class Application extends Injectable
{

    protected $controllerNamespace = '\\App\\Controller\\';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->setDi(FactoryContainer::instance());
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

            $dispatcher = new ControllerDispatcher($this->getDi());

            $dispatcher->setNamespace($this->getControllerNamespace());
            $dispatcher->setController($router->getController());
            $dispatcher->setAction($router->getAction());
            $dispatcher->setParams($router->getMatches());

            try {
                $content = $dispatcher->dispatch();

                if ($this->response->getBodyFormat() == Response::RESPONSE_HTML) {
                    if ($content === null) {
                        $this->view->addLayout("layouts/{$dispatcher->getController()}");
                        $content = $this->render("{$dispatcher->getController()}/{$dispatcher->getAction()}");
                    }
                    $this->response->setContent($content);
                }

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
     * @param $path
     * @return string
     * @throws \Dez\Http\Exception
     * @throws \Exception
     */
    protected function render($path)
    {
        $this->response->sendCookies()->sendHeaders();
        $content = null;

        if ($this->response->isEnableBody()) {
            $this->prepareView();
            $content = $this->view->render($path);
        }

        return $content;
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
     * @return \Dez\Config\Adapter\Ini|\Dez\Config\Adapter\Json|\Dez\Config\Adapter\NativeArray|Config
     * @throws \Dez\Config\Exception
     */
    public static function sampleConfiguration()
    {
        $configurationFilePath = realpath(__DIR__ . '/../sample.configuration.ini');

        $config = Config::factory($configurationFilePath);
        $config['config-file'] = $configurationFilePath;

        return $config;
    }

}