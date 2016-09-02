<?php

namespace Dez\Mvc\Controller;
use Dez\Mvc\Controller;

/**
 * Class ControllerResponse
 * @package Dez\Mvc\Controller
 */
class ControllerResponse {

    /**
     * @var Controller
     */
    protected $controllerInstance;

    /**
     * @var string|null
     */
    protected $controllerContent = null;

    /**
     * @return Controller
     */
    public function getControllerInstance()
    {
        return $this->controllerInstance;
    }

    /**
     * @param Controller $controllerInstance
     */
    public function setControllerInstance(Controller $controllerInstance)
    {
        $this->controllerInstance = $controllerInstance;
    }

    /**
     * @return null|string
     */
    public function getControllerContent()
    {
        return $this->controllerContent;
    }

    /**
     * @param null|string $controllerContent
     */
    public function setControllerContent($controllerContent)
    {
        $this->controllerContent = $controllerContent;
    }

}