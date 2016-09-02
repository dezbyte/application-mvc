<?php

namespace Dez\Mvc\Controller;

/**
 * Class RuntimeMvcException
 * @package Dez\Mvc\Controller
 */
class RuntimeMvcException extends \ErrorException {

    /**
     * RuntimeMvcException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

}