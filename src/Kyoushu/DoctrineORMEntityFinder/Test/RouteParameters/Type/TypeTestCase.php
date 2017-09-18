<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test\RouteParameters\Type;

use PHPUnit\Framework\TestCase;

abstract class TypeTestCase extends TestCase
{

    abstract public function testTransform();

    abstract public function testReverseTransform();

}