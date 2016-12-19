<?php

namespace Kyoushu\DoctrineORMEntityFinder;

use Doctrine\ORM\EntityManager;

class FinderFactory
{

    /**
     * @var array
     */
    private $registry;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->registry = array();
    }

    public function registerFinder($name, $finderClass)
    {
        if(!is_subclass_of($finderClass, 'Kyoushu\DoctrineORMEntityFinder\FinderInterface')){
            throw new FinderException(sprintf(
                '%s does not extend Kyoushu\DoctrineORMEntityFinder\FinderInterface',
                $finderClass
            ));
        }
        $this->registry[$name] = $finderClass;
    }

    /**
     * @param string $name
     * @return FinderInterface
     * @throws FinderException
     */
    public function createFinder($name)
    {
        if(!isset($this->registry[$name])){
            throw new FinderException(sprintf(
                '%s is not a registered finder name',
                $name
            ));
        }
        $class = $this->registry[$name];
        /** @var FinderInterface $finder */
        $finder = new $class;
        $finder->setEntityManager($this->entityManager);
        return $finder;
    }

}