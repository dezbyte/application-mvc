<?php

namespace Dez\Mvc\Controller;

use Dez\DependencyInjection\InjectableInterface;
use Dez\Mvc\InjectableAware;

interface ControllerInterface extends InjectableInterface, InjectableAware
{

    public function beforeExecute();

    public function afterExecute();

}