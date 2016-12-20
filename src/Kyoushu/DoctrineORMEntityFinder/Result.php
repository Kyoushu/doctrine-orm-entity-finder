<?php

namespace Kyoushu\DoctrineORMEntityFinder;

class Result implements ResultInterface
{

    /**
     * @var int
     */
    protected $index;

    /**
     * @var array|object[]
     */
    protected $entities;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $routeParameters;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int|null
     */
    protected $perPage;

    /**
     * @param array|object[] $entities
     * @param int $total
     * @param array $parameters
     * @param array $routeParameters
     * @param int $page
     * @param int|null $perPage
     */
    public function __construct(array $entities, $total, array $parameters, array $routeParameters, $page, $perPage)
    {
        $this->index = 0;
        $this->entities = array_values($entities);
        $this->total = (int)$total;
        $this->page = (int)$page;
        $this->perPage = ($perPage === null ? null : (int)$perPage);
        $this->parameters = $parameters;
        $this->routeParameters = $routeParameters;

    }

    /**
     * @return array|object[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        $perPage = $this->getPerPage();
        if($perPage === null) return null;
        $page = $this->getPage();
        $totalPages = $this->getTotalPages();
        if($page >= $totalPages) return null;
        return $page + 1;

    }

    /**
     * @return int|null
     */
    public function getPrevPage()
    {
        $perPage = $this->getPerPage();
        if($perPage === null) return null;
        $page = $this->getPage();
        if($page <= 1) return null;
        return $page - 1;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        $perPage = $this->getPerPage();
        if($perPage === null) return 1;
        $total = $this->getTotal();
        $totalPages =  ceil($total / $perPage);
        if($totalPages <= 0) return 1;
        return $totalPages;
    }

    // \Iterator implementation

    /**
     * @return array|object
     */
    public function current()
    {
        return $this->entities[$this->index];
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * @return int|null
     */
    public function key()
    {
        if(!$this->valid()) return null;
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->entities[$this->index]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    // \ArrayAccess implementation

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }

    /**
     * @param int $offset
     * @return array|object
     */
    public function offsetGet($offset)
    {
        return $this->entities[$offset];
    }

    /**
     * @param int $offset
     * @param array|object $value
     */
    public function offsetSet($offset, $value)
    {
        $this->entities[$offset] = $value;
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->entities[$offset]);
    }

    // \Countable interface

    public function count()
    {
        return count($this->entities);
    }

}