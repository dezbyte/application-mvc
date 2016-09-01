<?php

namespace Dez\Mvc\View;

use Dez\Mvc\Controller\MvcException;

/**
 * Class NullAdapter
 * @package Dez\Mvc\View
 */
class NullAdapter implements AdapterInterface {

    /**
     * @param $name
     * @param $data
     * @throws MvcException
     */
    public function set($name, $data)
    {
        throw new MvcException('This is null adapter. Please configure and override view object');
    }

    /**
     * @param array $data
     * @throws MvcException
     */
    public function batch(array $data = [])
    {
        throw new MvcException('This is null adapter. Please configure and override view object');
    }

    /**
     * @param $name
     * @throws MvcException
     */
    public function get($name)
    {
        throw new MvcException('This is null adapter. Please configure and override view object');
    }

    /**
     * @param $name
     * @throws MvcException
     */
    public function remove($name)
    {
        throw new MvcException('This is null adapter. Please configure and override view object');
    }

    /**
     * @param $name
     * @throws MvcException
     */
    public function render($name)
    {
        throw new MvcException('This is null adapter. Please configure and override view object');
    }

}