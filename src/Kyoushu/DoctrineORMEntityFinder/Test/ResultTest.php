<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test;

use Kyoushu\DoctrineORMEntityFinder\Result;

class ResultTest extends FinderTestCase
{

    protected function createResultMockEntities()
    {
        return array(
            $this->createMockEntity('Foo'),
            $this->createMockEntity('Bar'),
            $this->createMockEntity('Baz')
        );
    }

    public function testIteration()
    {
        $result = new Result($this->createResultMockEntities(), 3, array(), array(), 1, null);

        $names = array('Foo', 'Bar', 'Baz');

        $index = 0;
        foreach($result as $entity){
            $this->assertEquals($names[$index], $entity->getName());
            $index++;
        }
    }

    public function testGetTotalPages()
    {
        $result = new Result(array(), 50, array(), array(), 1, 10);

        $this->assertEquals(5, $result->getTotalPages());

        $result = new Result(array(), 0, array(), array(), 1, 10);

        $this->assertEquals(1, $result->getTotalPages());
    }

    public function testPagination()
    {
        $result = new Result(array(), 9, array(), array(), 1, 3);

        $this->assertEquals(3, $result->getPerPage());

        $this->assertEquals(1, $result->getPage());
        $this->assertEquals(2, $result->getNextPage());
        $this->assertEquals(null, $result->getPrevPage());

        $result = new Result(array(), 9, array(), array(), 2, 3);

        $this->assertEquals(2, $result->getPage());
        $this->assertEquals(3, $result->getNextPage());
        $this->assertEquals(1, $result->getPrevPage());

        $result = new Result(array(), 9, array(), array(), 3, 3);

        $this->assertEquals(3, $result->getPage());
        $this->assertEquals(null, $result->getNextPage());
        $this->assertEquals(2, $result->getPrevPage());

        $result = new Result(array(), 9, array(), array(), 1, null);

        $this->assertEquals(null, $result->getPerPage());

        $this->assertEquals(1, $result->getPage());
        $this->assertEquals(null, $result->getNextPage());
        $this->assertEquals(null, $result->getPrevPage());

        $result = new Result(array(), 0, array(), array(), 1, 1);

        $this->assertEquals(1, $result->getPage());
        $this->assertEquals(null, $result->getNextPage());
        $this->assertEquals(null, $result->getPrevPage());

        $result = new Result(array(), 0, array(), array(), 1, null);

        $this->assertEquals(1, $result->getPage());
        $this->assertEquals(null, $result->getNextPage());
        $this->assertEquals(null, $result->getPrevPage());
    }

}