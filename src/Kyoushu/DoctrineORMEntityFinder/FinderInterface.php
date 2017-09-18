<?php

namespace Kyoushu\DoctrineORMEntityFinder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\PropertyMap;

interface FinderInterface
{

    const ROUTE_NULL_PLACEHOLDER = '-';

    /**
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * @return int
     */
    public function getPage();

    /**
     * @param int $page
     * @return $this
     */
    public function setPage($page);

    /**
     * @return int|null
     */
    public function getPerPage();

    /**
     * @param int|null $perPage
     * @return $this
     */
    public function setPerPage($perPage);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param array $parameters
     * @return array
     */
    public function setParameters(array $parameters);

    /**
     * @return string[]
     */
    public function getParameterKeys();

    /**
     * @return array
     */
    public function getRouteParameters();

    /**
     * @return PropertyMap|null
     */
    public function createRouteParameterMap();

    /**
     * @param array $routeParameters
     * @return $this
     */
    public function setRouteParameters(array $routeParameters);

    /**
     * @return string
     */
    public function getEntityAlias();

    /**
     * @return string
     */
    public function getEntityClass();

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder();

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureQueryBuilder(QueryBuilder $queryBuilder);

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @param array $entities
     * @param int $total
     * @param array $parameters
     * @param array $routeParameters
     * @param int $page
     * @param int|null $perPage
     * @return mixed
     */
    public function createResult(array $entities, $total, array $parameters, array $routeParameters, $page, $perPage);

    /**
     * @param int $hydrationMode
     * @return ResultInterface
     */
    public function getResult($hydrationMode = Query::HYDRATE_OBJECT);

}