<?php

namespace Dez\Mvc\Application;

use Dez\Config\Config;
use Dez\Mvc\Application;
use Dez\Mvc\Controller\MvcException;
use Dez\Mvc\View\TemplateAdapter;
use Dez\ORM\Connection;
use Dez\Template\Template;

abstract class ConfigurableApplication extends Application
{

    public function __construct(Config $config)
    {
        parent::__construct();

        $this->config->merge($config);
    }

    public function configure()
    {
        /**
         * @var Config $serverConfig
         * @var Config $applicationConfig
        */
        if ($this->config->has('server')) {
            $serverConfig = $this->config->get('server');

            if ($serverConfig->has('timezone')) {
                date_default_timezone_set($serverConfig['timezone']);
            }

            if ($serverConfig->has('displayErrors')) {
                ini_set('display_errors', $serverConfig['displayErrors']);
            }

            if ($serverConfig->has('errorLevel')) {
                error_reporting($serverConfig['errorLevel']);
            }
        }

        if ($this->config->has('application')) {
            $applicationConfig = $this->config->get('application');

            if ($applicationConfig->has('autoload')) {
                $this->loader->registerNamespaces($applicationConfig->get('autoload')->toArray())
                    ->register();
            }

            if ($applicationConfig->has('controller')) {
                $this->setControllerNamespace($applicationConfig['controller']['namespace']);
            }

            if ($applicationConfig->has('base_path')) {
                $this->url->setBasePath($applicationConfig['base_path']);
                if ($applicationConfig->has('static_path')) {
                    $this->url->setStaticPath($applicationConfig['static_path']);
                }
            }

            if ($applicationConfig->has('view')) {
                $this->dependencyInjector->set('view', function() use ($applicationConfig) {
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
