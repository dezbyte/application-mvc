<?php

namespace Dez\Mvc\View;

use Dez\Config\Config;
use Dez\Mvc\Controller\MvcException;
use Dez\Template\Template;

/**
 * Class TemplateAdapter
 * @package Dez\Mvc\View
 */
class TemplateAdapter implements AdapterInterface {

    /**
     * @var Config
     */
    protected static $config = null;

    /**
     * @var Template
    */
    protected $template = null;

    /**
     * TemplateAdapter constructor.
     */
    public function __construct()
    {
        if(null === static::$config) {
            throw new MvcException('Template adapter not configured yet. Please pass configuration object before');
        }

        $this->template = new Template(static::$config->get('root_directory'));
    }

    /**
     * @param $name
     * @param $data
     * @return $this
     */
    public function set($name, $data)
    {
        $this->template->set($name, $data);

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function batch(array $data = [])
    {
        $this->template->batch($data);

        return $this;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name)
    {
        return $this->template->data()->get($name);
    }

    /**
     * @param $name
     * @return $this
     */
    public function remove($name)
    {
        $this->template->data()->get($name);

        return $this;
    }

    /**
     * @param $name
     * @return string
     */
    public function render($name)
    {
        return $this->template->render($name);
    }

    /**
     * @param Config $config
     */
    public static function configuration(Config $config)
    {
        static::$config = $config;
    }

}