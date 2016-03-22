<?php

namespace Dez\Mvc\GridRouteMapper\Adapter;

use Dez\Mvc\GridRouteMapper\MapperException;
use Dez\Mvc\GridRouteMapper\SourceAdapter;
use Dez\ORM\Model\QueryBuilder;

class OrmQuery extends SourceAdapter {

    /**
     * @var QueryBuilder
     */
    protected $source;

    /**
     * @param QueryBuilder $source
     * @throws MapperException
     */
    protected function setSourceData($source = null)
    {
        if(! ($source instanceof QueryBuilder)) {
            throw new MapperException("Source must be instance of Orm Table");
        }
        
        $this->source = $source;
    }

    protected function process()
    {
        
    }


}