<?php

namespace Kyoushu\DoctrineORMEntityFinder;

interface ResultInterface extends \Iterator, \ArrayAccess, \Countable
{

    /**
     * @return array|object[]
     */
    public function getEntities();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @return array
     */
    public function getRouteParameters();

    /**
     * @return int
     */
    public function getPage();

    /**
     * @return int
     */
    public function getPerPage();

    /**
     * @return int|null
     */
    public function getNextPage();

    /**
     * @return int|null
     */
    public function getPrevPage();

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return int
     */
    public function getTotalPages();

}