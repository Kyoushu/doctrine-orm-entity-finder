<?php

namespace Kyoushu\DoctrineORMEntityFinder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractFinder implements FinderInterface
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int|null
     */
    protected $perPage = null;

    /**
     * @return EntityManager
     * @throws FinderException
     */
    public function getEntityManager()
    {
        if(!$this->entityManager){
            throw new FinderException('Entity manager is not available');
        }
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return EntityRepository
     */
    private function getRepository()
    {
        return $this->getEntityManager()
            ->getRepository($this->getEntityClass());
    }

    /**
     * @return PropertyAccessor
     */
    private function createPropertyAccessor()
    {
        return PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = (int)$page;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int|null $perPage
     * @return $this
     */
    public function setPerPage($perPage)
    {
        if($perPage !== null) $perPage = (int)$perPage;
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $alias = $this->getEntityAlias();

        $queryBuilder = $this->getRepository()
            ->createQueryBuilder($alias);

        $this->configureQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        $alias = $this->getEntityAlias();
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(sprintf('COUNT(DISTINCT %s.id)', $alias));
        $queryBuilder->resetDQLPart('groupBy'); // Remove group by so we don't end up with multiple rows
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        $propertyAccessor = $this->createPropertyAccessor();
        $keys = $this->getParameterKeys();
        $parameters = array();
        foreach($keys as $key){
            $value = $propertyAccessor->getValue($this, $key);
            $parameters[$key] = $value;
        }
        return $parameters;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $propertyAccessor = $this->createPropertyAccessor();
        $keys = $this->getParameterKeys();
        foreach($keys as $key){
            $value = (isset($parameters[$key]) ? $parameters[$key] : null);
            $propertyAccessor->setValue($this, $key, $value);
        }
        return $this;
    }

    /**
     * @param array $routeParameters
     * @return $this
     */
    public function setRouteParameters(array $routeParameters)
    {
        $nullPlaceholder = static::ROUTE_NULL_PLACEHOLDER;
        array_walk($routeParameters, function(&$value) use ($nullPlaceholder){
            if($value === $nullPlaceholder) $value = null;
        });
        $this->setParameters($routeParameters);
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        $parameters = $this->getParameters();
        $nullPlaceholder = static::ROUTE_NULL_PLACEHOLDER;
        array_walk($parameters, function(&$value) use ($nullPlaceholder){
            if($value === null) $value = $nullPlaceholder;
        });
        return $parameters;
    }

    /**
     * @param array $entities
     * @param int $total
     * @param array $parameters
     * @param array $routeParameters
     * @param int $page
     * @param int|null $perPage
     * @return ResultInterface
     */
    public function createResult(array $entities, $total, array $parameters, array $routeParameters, $page, $perPage)
    {
        return new Result($entities, $total, $parameters, $routeParameters, $page, $perPage);
    }

    private function getResultIds()
    {
        $query = $this->createQueryBuilder()
            ->select(sprintf('DISTINCT %s.id', $this->getEntityAlias()))
            ->getQuery();

        $result = $query->getArrayResult();

        $ids = array();
        foreach($result as $row){
            $ids[] = $row['id'];
        }

        return $ids;
    }

    /**
     * @param array $ids
     * @param int $hydrationMode
     * @return array|object[]
     */
    private function findEntitiesByIds(array $ids, $hydrationMode)
    {
        $query = $this
            ->createQueryBuilder()
            ->andWhere(sprintf('%s.id IN (:ids)', $this->getEntityAlias()))
            ->setParameter('ids', $ids)
            ->getQuery();

        $result = $query->getResult($hydrationMode);

        return $result;
    }

    /**
     * @param int $hydrationMode
     * @return ResultInterface
     */
    public function getResult($hydrationMode = Query::HYDRATE_OBJECT)
    {
        $query = $this->createQueryBuilder()->getQuery();
        $query->setHydrationMode($hydrationMode);

        $page = $this->getPage();
        $perPage = $this->getPerPage();

        if($perPage === null){
            $entities = $query->getResult($hydrationMode);
        }
        else{
            $offset = ($page - 1) * $perPage;
            $ids = $this->getResultIds();
            $ids = array_slice($ids, $offset, $perPage);
            $entities = $this->findEntitiesByIds($ids, $hydrationMode);
        }

        $total = $this->getTotal();

        return $this->createResult($entities, $total, $this->getParameters(), $this->getRouteParameters(), $page, $perPage);
    }

    /**
     * @return string
     */
    public function getEntityAlias()
    {
        return 'entity';
    }

}