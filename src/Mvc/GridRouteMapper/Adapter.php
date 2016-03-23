<?php

namespace Dez\Mvc\GridRouteMapper;

abstract class Adapter
{

    public function __construct($source = null)
    {
        $this->setSourceData($source);
    }

    static public $criteria = [
        Mapper::MAPPER_EQUAL => '=',
        Mapper::MAPPER_LIKE => 'LIKE',
        Mapper::MAPPER_NOT_LIKE => 'NOT LIKE',
        Mapper::MAPPER_GREATER_THAN => '>',
        Mapper::MAPPER_GREATER_THAN_EQUAL => '>=',
        Mapper::MAPPER_LESS_THAN => '<',
        Mapper::MAPPER_LESS_THAN_EQUAL => '<=',
        Mapper::MAPPER_NOT_EQUAL => '!=',
    ];

    abstract protected function setSourceData($data = null);

    abstract public function process(array $params = []);

    abstract public function getData();

}