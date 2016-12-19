# Doctrine ORM Entity Finder

Boilerplate for building finder classes to be used with Doctrine ORM

## Class Example

```php
<?php

namespace App;

use Kyoushu\DoctrineORMEntityFinder\Test\AbstractFinder;
use Doctrine\ORM\QueryBuilder;

class MyFinder extends AbstractFinder
{
    
    protected $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getParameterKeys()
    {
        return array('name');
    }
    
    public function getEntityClass()
    {
        return 'App\Entity\MyEntity';        
    }
    
    public function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
        $name = $this->name;
        if($name){
            $queryBuilder->andWhere('entity.name = :name');
            $queryBuilder->setParameter('name', $name);
        }
    }
    
}
```

### Usage Example

```php
<?php

namespace App;

use Kyoushu\DoctrineORMEntityFinder\FinderFactory;

// Create finder factory
$factory = new FinderFactory($entityManager);

// Register finder class
$factory->registerFinder('my_finder', 'App\MyFinder');

// Create finder and configure it to find entities named Foo
$finder = $factory->createFinder('my_finder');
$finder->setPage(1)->setPerPage(5)->setName('Foo');

// Get search results
$result = $finder->getResult();

// Generate a URL using parameters from the finder
$url = $router->generate('search', $finder->getRouteParameters());
```