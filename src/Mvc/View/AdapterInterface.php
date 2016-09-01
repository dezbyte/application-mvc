<?php

namespace Dez\Mvc\View;

interface AdapterInterface {

    public function set($name, $data);

    public function batch(array $data = []);

    public function get($name);

    public function remove($name);

    public function render($name);

}