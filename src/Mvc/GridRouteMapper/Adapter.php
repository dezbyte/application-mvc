<?php

namespace Dez\Mvc\GridRouteMapper;

abstract class Adapter
{

    /**
     * Adapter constructor.
     * @param null $source
     */
    public function __construct($source = null)
    {
        $this->setSourceData($source);
    }

    /**
     * @var array
     */
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

    /**
     * @param null $data
     * @return $this
     */
    abstract protected function setSourceData($data = null);

    /**
     * @param array $params
     * @return $this
     */
    abstract public function process(array $params = []);

    /**
     * @return mixed
     */
    abstract public function getData();

}