<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

class FinderTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @param null|string $name
     * @return MockEntity
     */
    protected function createMockEntity($name = null)
    {
        $entity = new MockEntity();
        $entity->setName($name);
        return $entity;
    }

    /**
     * @return EntityManager
     */
    protected function createEntityManager()
    {
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__), true, null, null, false);
        $conn = array('url' => 'sqlite:///:memory:');
        $entityManager = EntityManager::create($conn, $config);
        $this->updateSchema($entityManager);
        return $entityManager;
    }

    private function updateSchema(EntityManager $entityManager)
    {
        /** @var EntityManager $entityManager */
        $schemaManager = $entityManager->getConnection()->getSchemaManager();
        $schemaManager->createDatabase('test');
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadatas, true);
    }

}