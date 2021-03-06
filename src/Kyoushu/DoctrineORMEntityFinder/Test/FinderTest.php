<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Kyoushu\MockRouteParameterMappedFinder;

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

    public function testParameters()
    {
        $finder = new MockFinder();

        $finder->setPage(1)->setPerPage(2)->setName('Foo');

        $this->assertEquals(
            array(
                'page' => 1,
                'perPage' => 2,
                'name' => 'Foo',
                'created' => null
            ),
            $finder->getParameters()
        );

        $finder = new MockFinder();

        $finder->setParameters(array(
            'page' => 3,
            'perPage' => 12,
            'name' => 'Bar'
        ));

        $this->assertEquals(3, $finder->getPage());
        $this->assertEquals(12, $finder->getPerPage());
        $this->assertEquals('Bar', $finder->getName());
    }

    public function testRouteParameters()
    {
        $finder = new MockFinder();

        $finder->setPage(1)->setPerPage(2)->setName(null);

        $this->assertEquals(
            array(
                'page' => 1,
                'perPage' => 2,
                'name' => '-',
                'created' => '-'
            ),
            $finder->getRouteParameters()
        );

        $finder = new MockFinder();

        $finder->setRouteParameters(array(
            'page' => 1,
            'perPage' => '-',
            'name' => 'Baz'
        ));

        $this->assertEquals(1, $finder->getPage());
        $this->assertEquals(null, $finder->getPerPage());
        $this->assertEquals('Baz', $finder->getName());

        $finder = new MockFinder();

        $finder->setRouteParameters(array(
            'name' => '0'
        ));

        $this->assertNotNull($finder->getName());
        $this->assertEquals(0, $finder->getName());

        $routeParameters = $finder->getRouteParameters();
        $this->assertEquals(0, $routeParameters['name']);
    }

    public function testDateTimeRouteParameter()
    {
        $finder = new MockFinder();

        $routeParameters = $finder->getRouteParameters();
        $this->assertEquals('-', $routeParameters['created']);

        $finder->setCreated(new \DateTime('2017-01-01T13:00:00+00:00'));
        $routeParameters = $finder->getRouteParameters();
        $this->assertEquals('2017-01-01T13:00:00+00:00', $routeParameters['created']);

        $routeParameters = array('created' => '2018-08-12T13:23:14+00:00');
        $finder->setCreated(null);
        $finder->setRouteParameters($routeParameters);

        $created = $finder->getCreated();
        $this->assertInstanceOf('\DateTime', $created);
        $this->assertEquals('12/08/2018 13:23:14', $created->format('d/m/Y H:i:s'));
    }

    public function testGroupingFinder()
    {
        $entityManager = $this->createEntityManager();

        $entities = array(
            (new MockEntity())
                ->setName('first entity')
                ->addChild((new MockChildEntity())->setName('first child'))
                ->addChild((new MockChildEntity())->setName('second child')),
            (new MockEntity())
                ->setName('second entity')
                ->addChild((new MockChildEntity())->setName('third child'))
                ->addChild((new MockChildEntity())->setName('fourth child'))
        );

        foreach($entities as $entity){
            $entityManager->persist($entity);
        }
        $entityManager->flush();

        $finder = new MockGroupingFinder();
        $finder->setEntityManager($entityManager);
        $finder->setPerPage(null);

        $this->assertEquals(2, $finder->getTotal());

        $finder->setPerPage(1);

        $this->assertEquals(2, $finder->getTotal());
    }

    public function testRouteParameterMap()
    {
        $finder = new MockRouteParameterMappedFinder();

        $this->assertEquals([
            'name' => '-',
            'created' => '-',
            'published' => '-',
            'attributes' => '-'
        ], $finder->getRouteParameters());

        $finder->setName('foo');
        $finder->setCreated(new \DateTime('2017-01-01 12:15:30'));
        $finder->setAttributes(['foo','bar','baz']);
        $finder->setPublished(true);

        $this->assertEquals([
            'name' => 'foo',
            'created' => '2017-01-01T12:15:30+00:00',
            'published' => '1',
            'attributes' => 'foo,bar,baz'
        ], $finder->getRouteParameters());

        $finder = new MockRouteParameterMappedFinder();

        $finder->setRouteParameters([
            'name' => 'foo',
            'created' => '2017-01-01T12:15:30+00:00',
            'published' => '1',
            'attributes' => 'foo,bar,baz'
        ]);

        $this->assertEquals('foo', $finder->getName());
        $this->assertEquals('2017-01-01 12:15:30', $finder->getCreated()->format('Y-m-d H:i:s'));
        $this->assertTrue($finder->getPublished());
        $this->assertEquals(['foo','bar','baz'], $finder->getAttributes());

    }

    public function testConfigureResultIdsQueryBuilder()
    {
        $entityManager = $this->createEntityManager();

        $finder = new MockFinder();
        $finder->setEntityManager($entityManager);

        $queryBuilder = $finder->createQueryBuilder();
        $queryBuilder->orderBy('entity.name', 'ASC');
        $finder->configureResultIdsQueryBuilder($queryBuilder);

        $selectParts = $queryBuilder->getDQLPart('select');
        $this->assertCount(2, $selectParts);

        $this->assertEquals('DISTINCT entity.id', $selectParts[0]->getParts()[0]);
        $this->assertEquals('entity.name', $selectParts[1]->getParts()[0]);

        $queryBuilder = $finder->createQueryBuilder();
        $queryBuilder->orderBy('entity.id', 'DESC');
        $finder->configureResultIdsQueryBuilder($queryBuilder);

        $selectParts = $queryBuilder->getDQLPart('select');
        $this->assertCount(1, $selectParts);

        $this->assertEquals('DISTINCT entity.id', $selectParts[0]->getParts()[0]);

    }

}