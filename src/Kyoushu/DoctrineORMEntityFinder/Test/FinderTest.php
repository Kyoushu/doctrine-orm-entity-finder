<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

class FinderTest extends FinderTestCase
{

    protected function createMockFinder()
    {
        $entityManager = $this->createEntityManager();

        $entities = array(
            $this->createMockEntity('Foo'),
            $this->createMockEntity('Foo'),
            $this->createMockEntity('Bar'),
            $this->createMockEntity('Baz')
        );

        foreach($entities as $entity){
            $entityManager->persist($entity);
        }
        $entityManager->flush();

        $finder = new MockFinder();
        $finder->setEntityManager($entityManager);

        return $finder;
    }

    public function testGetTotal()
    {
        $finder = $this->createMockFinder();

        $this->assertEquals(4, $finder->getTotal());
        $finder->setName('Foo');
        $this->assertEquals(2, $finder->getTotal());
        $finder->setName('Bar');
        $this->assertEquals(1, $finder->getTotal());
    }

}