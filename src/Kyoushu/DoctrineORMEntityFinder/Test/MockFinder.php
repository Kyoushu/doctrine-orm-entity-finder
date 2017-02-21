<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Doctrine\ORM\QueryBuilder;
use Kyoushu\DoctrineORMEntityFinder\AbstractFinder;

class MockFinder extends AbstractFinder
{

    /**
     * @var null|string
     */
    protected $name = null;

    /**
     * @var null|\DateTime
     */
    protected $created = null;

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
     * @return \DateTime|null
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime|null $created
     * @return $this
     */
    public function setCreated(\DateTime $created = null)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameterKeys()
    {
        return array('name', 'page', 'perPage', 'created');
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

        $queryBuilder->leftJoin('entity.children', 'child');
        $queryBuilder->addSelect('child');

        $queryBuilder->orderBy('entity.name', 'ASC');
    }

}