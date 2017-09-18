<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\BoolType;

class BoolTypeTest extends TypeTestCase
{

    public function testTransform()
    {
        $type = new BoolType();

        $this->assertEquals('-', $type->transform(null));
        $this->assertEquals('1', $type->transform(true));
        $this->assertEquals('-', $type->transform(false));
    }

    public function testReverseTransform()
    {
        $type = new BoolType();

        $this->assertEquals(null, $type->reverseTransform('-'));
        $this->assertEquals(null, $type->reverseTransform('0'));
        $this->assertEquals(null, $type->reverseTransform(null));
        $this->assertEquals(true, $type->reverseTransform('1'));
    }

}