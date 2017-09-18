<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\CommaSepArrayType;

class CommaSepArrayTypeTest extends TypeTestCase
{

    public function testTransform()
    {
        $type = new CommaSepArrayType();

        $this->assertEquals('-', $type->transform(null));
        $this->assertEquals('foo,bar,baz', $type->transform(['foo','bar','baz']));
    }

    public function testReverseTransform()
    {
        $type = new CommaSepArrayType();

        $this->assertEquals([], $type->reverseTransform('-'));
        $this->assertEquals(['foo','bar','baz'], $type->reverseTransform('foo,bar,baz'));
    }

}