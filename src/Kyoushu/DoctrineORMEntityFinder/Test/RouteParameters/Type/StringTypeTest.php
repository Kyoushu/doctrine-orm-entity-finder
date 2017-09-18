<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\StringType;

class StringTypeTest extends TypeTestCase
{

    public function testTransform()
    {
        $type = new StringType();

        $this->assertEquals('-', $type->transform(null));
        $this->assertEquals('foo', $type->transform('foo'));
        $this->assertEquals('1', $type->transform(1));
    }

    public function testReverseTransform()
    {
        $type = new StringType();

        $this->assertEquals(null, $type->reverseTransform('-'));
        $this->assertEquals('foo', $type->reverseTransform('foo'));
    }

}