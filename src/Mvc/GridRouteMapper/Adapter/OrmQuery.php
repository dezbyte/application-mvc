<?php

namespace Dez\Mvc\GridRouteMapper\Adapter;

use Dez\Mvc\GridRouteMapper\MapperException;
use Dez\Mvc\GridRouteMapper\Adapter;
use Dez\ORM\Model\QueryBuilder;

class OrmQuery extends Adapter {

    /**
     * @var QueryBuilder
     */
    protected $query;

    /**
     * @param QueryBuilder $query
     * @return $this
     * @throws MapperException
     */
    protected function setSourceData($query = null)
    {
        if(! ($query instanceof QueryBuilder)) {
            throw new MapperException("Source must be instance of Orm Table");
        }
        
        $this->query = $query;

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function process(array $params = [])
    {

        $filter = $params['filter'];
        $order = $params['order'];

        if(count($filter) > 0) {
            foreach($filter as $column => $conditions) {
                foreach($conditions as $criterion => $value) {
                    $this->query->where($column, $value, static::$criteria[$criterion]);
                }
            }
        }

        if(count($order) > 0) {
            foreach($order as $column => $vector) {
                $this->query->order($column, $vector);
            }
        }

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function getData()
    {
        return $this->query;
    }


}