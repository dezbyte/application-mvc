<?php

namespace Dez\Mvc\GridRouteMapper;

use Dez\DependencyInjection\Injectable;
use Dez\Http\Request;
use Dez\Router\Router;
use Dez\Url\Url;

abstract class Mapper extends Injectable
{

    const MAPPER_IDENTITY = 'grid-mapper';

    const MAPPER_ORDER_DESC = 'desc';
    const MAPPER_ORDER_ASC = 'asc';

    const MAPPER_LIKE = 'lk';
    const MAPPER_NOT_LIKE = 'nl';

    const MAPPER_NULL = 'null';

    const MAPPER_EQUAL = 'eq';
    const MAPPER_NOT_EQUAL = 'ne';
    const MAPPER_GREATER_THAN = 'gt';
    const MAPPER_GREATER_THAN_EQUAL = 'ge';
    const MAPPER_LESS_THAN = 'lt';
    const MAPPER_LESS_THAN_EQUAL = 'le';

    /**
     * @var null
     */
    protected $dataSource = null;

    /**
     * @var array
     */
    protected $allowedFilter = [];

    /**
     * @var array
     */
    protected $allowedOrder = [];

    /**
     * @var null
     */
    protected $uniqueIdentity = null;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var array
     */
    protected $filter = [];

    /**
     * @var array
     */
    protected $order = [];

    /**
     * @var string
     */
    protected $rootUrlPath = '/';

    /**
     * @var array
     */
    protected $toBuild = [
        'filter' => [],
        'order' => [],
    ];

    /**
     * Mapper constructor.
     */
    public function __construct()
    {
        if ($this->uniqueIdentity !== null) {
            $this->setPrefix("{$this->getUniqueIdentity()}-");
        }
    }

    /**
     * @return null
     */
    public function getUniqueIdentity()
    {
        return $this->uniqueIdentity;
    }

    /**
     * @param null $uniqueIdentity
     * @return static
     */
    public function setUniqueIdentity($uniqueIdentity)
    {
        $this->uniqueIdentity = $uniqueIdentity;

        if ($this->uniqueIdentity !== null) {
            $this->setPrefix("{$this->getUniqueIdentity()}-");
        }

        return $this;
    }

    /**
     * @throws MapperException
     */
    public function processRequestParams()
    {
        /** @var Request $request */
        $request = $this->getDi()->get('request');
        /** @var Router $router */
        $router = $this->getDi()->get('router');

        if (null === $request || null === $router) {
            throw new MapperException("Request or Router is required for mapper");
        }

        $dirtyMatches = $router->getDirtyMatches();

        $index = array_search(Mapper::MAPPER_IDENTITY, $dirtyMatches);

        if ($index !== false) {

            $matches = array_slice($dirtyMatches, $index + 1);
            $matches = array_column(array_chunk($matches, 2), 1, 0);

            foreach ($this->getAllowedFilter() as $filterColumn) {
                $conditions = $request->getFromArray($matches, "{$this->getPrefix()}filter-{$filterColumn}", null);

                if (null !== $conditions) {
                    $conditions = array_chunk(explode('-', $conditions), 2);
                    $conditions = array_column($conditions, 1, 0);

                    foreach ($conditions as $criterion => $value) {
                        if ($this->checkFilterCriterion($criterion)) {
                            $this->setFilter($filterColumn, $criterion, $value);
                        }
                    }
                }
            }

            foreach ($this->getAllowedOrder() as $orderColumn) {
                $founded = $request->getFromArray($matches, "{$this->getPrefix()}order-{$orderColumn}", null);

                if (null !== $founded) {
                    $this->setOrder($orderColumn, $founded);
                }
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedFilter()
    {
        return $this->allowedFilter;
    }

    /**
     * @param array $allowedFilter
     * @return static
     */
    public function setAllowedFilter(array $allowedFilter = [])
    {
        $this->allowedFilter = $allowedFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return static
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @param string $criterion
     * @return mixed
     */
    public function checkFilterCriterion($criterion = Mapper::MAPPER_EQUAL)
    {
        return in_array($criterion, $this->getFilterCriteria(), true);
    }

    /**
     * @return array
     */
    public function getFilterCriteria()
    {
        return [
            Mapper::MAPPER_EQUAL,
            Mapper::MAPPER_NOT_EQUAL,
            Mapper::MAPPER_GREATER_THAN,
            Mapper::MAPPER_GREATER_THAN_EQUAL,
            Mapper::MAPPER_LESS_THAN,
            Mapper::MAPPER_LESS_THAN_EQUAL,
            Mapper::MAPPER_LIKE,
            Mapper::MAPPER_NOT_LIKE,
            Mapper::MAPPER_NULL,
        ];
    }

    /**
     * @return array
     */
    public function getAllowedOrder()
    {
        return $this->allowedOrder;
    }

    /**
     * @param array $allowedOrder
     * @return static
     */
    public function setAllowedOrder(array $allowedOrder = [])
    {
        $this->allowedOrder = $allowedOrder;

        return $this;
    }

    /**
     * @return mixed
     * @throws MapperException
     */
    public function processDataSource()
    {
        if (!($this->dataSource instanceof Adapter)) {
            throw new MapperException("Initialize before data source adapter");
        }

        $dataSourceParams = [
            'filter' => $this->getFilter(),
            'order' => $this->getOrder(),
        ];

        $this->getDataSource()->process($dataSourceParams);

        return $this->getDataSource()->getData();
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param $column
     * @param string $criterion
     * @param null $value
     * @return Mapper
     */
    public function setFilter($column, $criterion = Mapper::MAPPER_EQUAL, $value = null)
    {
        if ($this->checkFilterCriterion($criterion)) {
            $this->filter[$column][$criterion] = $value;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param $column
     * @param string $vector
     * @return static
     */
    public function setOrder($column, $vector = Mapper::MAPPER_ORDER_ASC)
    {
        $this->order[$column] = $vector;

        return $this;
    }

    /**
     * @return Adapter
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param Adapter $dataSource
     */
    public function setDataSource(Adapter $dataSource = null)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @return string
     */
    public function getRootUrlPath()
    {
        return $this->rootUrlPath;
    }

    /**
     * @param string $rootUrlPath
     * @return static
     */
    public function setRootUrlPath($rootUrlPath)
    {
        $this->rootUrlPath = $rootUrlPath;

        return $this;
    }

    /**
     * @param string $path
     * @return Mapper
     */
    public function path($path = '/')
    {
        return $this->setRootUrlPath($path);
    }

    /**
     * @return string
     */
    public function url()
    {

        $path = $this->getRootUrlPath();

        if ($this->hasRequestRoute()) {

            $parameters = $this->toBuild;
            $urlPartials = [];

            if(count($parameters['filter']) > 0) foreach ($parameters['filter'] as $column => $filter) {

                $urlPartials[] = "{$this->getPrefix()}filter-{$column}";
                $filterConditions = [];

                foreach ($filter as $criterion => $value) {
                    $filterConditions[] = "{$criterion}-{$value}";
                }

                $urlPartials[] = implode('-', $filterConditions);

            }

            if(count($parameters['order']) > 0) foreach ($parameters['order'] as $column => $vector) {
                $urlPartials[] = "{$this->getPrefix()}order-{$column}/{$vector}";
            }

            $filterRoute = implode('/', $urlPartials);

            $identity = Mapper::MAPPER_IDENTITY;

            $path = "{$path}/{$identity}/{$filterRoute}";
        }

        /** @var Url $url */
        $url = $this->getDi()->get('url');

        return $url->path($path);
    }

    /**
     * @return boolean
     */
    public function hasRequestRoute()
    {
        return (count($this->toBuild) !== count($this->toBuild, true));
    }

    /**
     * @param $column
     * @param string $vector
     * @param bool $reset
     * @return Mapper
     */
    public function order($column, $vector = Mapper::MAPPER_ORDER_ASC, $reset = true)
    {
        $order = &$this->toBuild['order'];
        $order = [];

        if (false === $reset) {
            $order = $this->getOrder();
        }

        $order[$column] = $vector;

        return $this;
    }

    /**
     * @param $column
     * @param string $criterion
     * @param null $value
     * @param bool $reset
     * @return Mapper
     */
    public function filter($column, $criterion = Mapper::MAPPER_EQUAL, $value = null, $reset = true)
    {
        $filter = &$this->toBuild['filter'];

        if (true === $reset) {
            $filter = [];
        }

        $filter[$column][$criterion] = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->getDataSource()->getData();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->url();
    }

}