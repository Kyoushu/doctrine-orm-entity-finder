<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Doctrine\ORM\QueryBuilder;
use Kyoushu\DoctrineORMEntityFinder\AbstractFinder;

class MockFinder extends AbstractFinder
{

    protected $name = null;

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameterKeys()
    {
        return array('name', 'page', 'perPage');
    }

    public function getEntityClass()
    {
        return 'Kyoushu\DoctrineORMEntityFinder\Test\MockEntity';
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
        $name = $this->name;
        if($name){
            $queryBuilder->andWhere('entity.name = :name');
            $queryBuilder->setParameter('name', $name);
        }

        $queryBuilder->orderBy('entity.name', 'ASC');
    }

}