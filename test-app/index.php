<?php

namespace TestApp;

// composer autoload
use Dez\Config\Config;
use Dez\Mvc\Application\ConfigurableApplication;

include_once '../vendor/autoload.php';

set_exception_handler(function (\Exception $exception) {
    die(get_class($exception) .': '. $exception->getMessage());
});

class TestApp extends ConfigurableApplication {

    public function initialize()
    {
        return $this;
    }

    public function injection()
    {
        return $this;
    }

}

(new TestApp(Config::factory(__DIR__ . '/config/app.php')))
    ->initialize()->injection()->configure()->run();