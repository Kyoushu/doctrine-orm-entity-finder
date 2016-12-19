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

    public function testGetResultPaginated()
    {
        $finder = $this->createMockFinder();

        $finder->setPerPage(2)->setPage(1);
        $result = $finder->getResult();

        $this->assertCount(2, $result);

        $this->assertEquals(3, $result[0]->getId());
        $this->assertEquals(4, $result[1]->getId());

        $finder->setPerPage(2)->setPage(2);
        $result = $finder->getResult();

        $this->assertCount(2, $result);

        $this->assertEquals(1, $result[0]->getId());
        $this->assertEquals(2, $result[1]->getId());

        $finder->setPerPage(2)->setPage(3);
        $result = $finder->getResult();

        $this->assertCount(0, $result);
    }

}