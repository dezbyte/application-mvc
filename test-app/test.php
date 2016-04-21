<?php

use Dez\Mvc\Application;
use Dez\Mvc\FactoryContainer;

include_once '../vendor/autoload.php';

new FactoryContainer();

die(var_dump(
    Application::sampleConfiguration(),
    Application::sampleConfiguration()->toJSON(),
    Application::sampleConfiguration()->toPHP()
));