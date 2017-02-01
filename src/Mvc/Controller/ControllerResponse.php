<?php

namespace Dez\Mvc\Controller;

use Dez\Mvc\Controller;

/**
 * Class ControllerResponse
 * @package Dez\Mvc\Controller
 */
class ControllerResponse
{
  
  /**
   * @var ControllerInterface
   */
  protected $controllerInstance;
  
  /**
   * @var string|null
   */
  protected $controllerContent = null;
  
  /**
   * @return ControllerInterface
   */
  public function getControllerInstance()
  {
    return $this->controllerInstance;
  }
  
  /**
   * @param ControllerInterface $controllerInstance
   */
  public function setControllerInstance(ControllerInterface $controllerInstance)
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