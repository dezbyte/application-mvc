<?php

namespace TestApp;

// composer autoload
use Dez\Config\Config;
use Dez\Mvc\Application\ConfigurableApplication;
use Dez\Mvc\Controller\RuntimeMvcException;

include_once '../vendor/autoload.php';

set_exception_handler(function (\Exception $exception) {
    die(get_class($exception) .': '. $exception->getMessage());
});

class TestApp extends ConfigurableApplication {
  protected function boot()
  {
    
  }
}

(new TestApp(Config::factory(__DIR__ . '/config/app.php')))
    ->initialize()->injection()->configure()->run();