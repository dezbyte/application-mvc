<?php

namespace Dez\Mvc\GridRouteMapper;

abstract class SourceAdapter
{

    public function __construct($source = null)
    {
        $this->setSourceData($source);
    }

    static public $criteria = [
        Mapper::MAPPER_EQUAL => '=',
        Mapper::MAPPER_LIKE => 'LIKE',
        Mapper::MAPPER_GREATER_THAN => '>',
        Mapper::MAPPER_GREATER_THAN_EQUAL => '>=',
        Mapper::MAPPER_LESS_THAN => '<',
        Mapper::MAPPER_LESS_THAN_EQUAL => '<=',
        Mapper::MAPPER_NOT_EQUAL => '!=',
        Mapper::MAPPER_ENUM => 'IN(%s)'
    ];

    abstract protected function setSourceData($data = null);

    abstract protected function process();

}