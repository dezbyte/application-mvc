<?php

namespace Dez\Mvc;

use Dez\EventDispatcher\Event;

/**
 * Class MvcEvent
 * @package Dez\Mvc
 */
class MvcEvent extends Event
{
  
  const ON_BEFORE_RUN = 'beforeActionRun';
  
  const ON_AFTER_RUN = 'afterActionRun';
  
  const ON_ACTION_ERROR = 'onActionRuntimeError';
  
  const ON_BEFORE_APP_RUN = 'beforeApplicationRun';
  
  const ON_AFTER_APP_RUN = 'afterApplicationRun';
  
  const ON_PAGE_404 = 'onPageNotFound';
  
  const ON_DISPATCHER_ERROR = 'onDispatcherError';
  
  /**
   * @var mixed
   */
  protected $target;
  
  /**
   * MvcEvent constructor.
   * @param $target
   */
  public function __construct($target)
  {
    $this->target = $target;
  }
  
  /**
   * @return mixed
   */
  public function getTarget()
  {
    return $this->target;
  }
  
}