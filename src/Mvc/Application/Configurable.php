<?php

namespace Dez\Mvc\Application;

use Dez\Config\Config;
use Dez\Mvc\Application;
use Dez\ORM\Connection;
use Dez\View\Engine\Php;

abstract class Configurable extends Application {

    protected $ormConnectionName = 'development';

    /**
     * SiteDezzApplication constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        parent::__construct();

        $this->config->merge($config);
    }

    /**
     * @return $this
     * @throws \Dez\View\Exception
     */
    public function configure()
    {
        if ($this->config->has('server')) {
            $serverConfig = $this->config->get('server');

            if($serverConfig->has('timezone')) {
                date_default_timezone_set($serverConfig['timezone']);
            }

            if($serverConfig->has('displayErrors')) {
                ini_set('display_errors', $serverConfig['displayErrors']);
            }

            if($serverConfig->has('errorLevel')) {
                error_reporting($serverConfig['errorLevel']);
            }
        }

        if ($this->config->has('application')) {
            $applicationConfig = $this->config->get('application');

            if ($applicationConfig->has('autoload')) {
                $this->loader->registerNamespaces($applicationConfig->get('autoload')->toArray())->register();
            }

            if ($applicationConfig->has('controllerNamespace')) {
                $this->setControllerNamespace($applicationConfig['controllerNamespace']);
            }

            if($applicationConfig->has('basePath')) {
                $this->url->setBasePath($applicationConfig['basePath']);

                if($applicationConfig->has('staticPath')) {
                    $this->url->setStaticPath($applicationConfig['staticPath']);
                }
            }

            if($applicationConfig->has('viewDirectory')) {
                $this->view->setViewDirectory($applicationConfig['viewDirectory']);

                $engine = new Php($this->view);
                $this->view->registerEngine('.php', $engine);
                $this->view->registerEngine('.phtml', $engine);
            }
        }

        if ($this->config->has('db')) {
            Connection::init($this->config, $this->getOrmConnectionName());
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getOrmConnectionName()
    {
        return $this->ormConnectionName;
    }

    /**
     * @param string $ormConnectionName
     * @return static
     */
    public function setOrmConnectionName($ormConnectionName)
    {
        $this->ormConnectionName = $ormConnectionName;

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