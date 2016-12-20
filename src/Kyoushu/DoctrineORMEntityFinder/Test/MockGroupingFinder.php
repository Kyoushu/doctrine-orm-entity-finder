<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Doctrine\ORM\QueryBuilder;

class MockGroupingFinder extends MockFinder
{

    public function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
        parent::configureQueryBuilder($queryBuilder);

        $queryBuilder->groupBy('entity.id');
    }

}