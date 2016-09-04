<?php

namespace Dez\Mvc\Controller;

/**
 * Class RuntimeMvcException
 * @package Dez\Mvc\Controller
 */
class RuntimeMvcException extends ReplaceException {

    /**
     * RuntimeMvcException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

}