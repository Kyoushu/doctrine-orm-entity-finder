<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test\RouteParameters;

use Kyoushu\DoctrineORMEntityFinder\RouteParameters\PropertyMap;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\DateTimeType;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\StringType;
use PHPUnit\Framework\TestCase;

class PropertyMapTest extends TestCase
{

    public function testApplyRouteParameters()
    {
        $map = new PropertyMap();
        $map->addProperty('text', new StringType());
        $map->addProperty('date', new DateTimeType());

        $context = new MockContext();

        $map->applyRouteParameters($context, [
            'text' => 'foo',
            'date' => '2017-01-02T12:32:43+00:00'
        ]);

        $this->assertEquals('foo', $context->getText());
        $this->assertEquals('2017-01-02 12:32:43', $context->getDate()->format('Y-m-d H:i:s'));

        $map->applyRouteParameters($context, [
            'text' => '-',
            'date' => '-'
        ]);

        $this->assertNull($context->getText());
        $this->assertNull($context->getDate());
    }

    public function testCreateRouteParameters()
    {
        $map = new PropertyMap();
        $map->addProperty('text', new StringType());
        $map->addProperty('date', new DateTimeType());

        $context = new MockContext();

        $this->assertEquals(['text' => '-', 'date' => '-'], $map->createRouteParameters($context));

        $context->setText('foo');
        $context->setDate(new \DateTime('2017-01-02 12:32:43'));

        $this->assertEquals(['text' => 'foo', 'date' => '2017-01-02T12:32:43+00:00'], $map->createRouteParameters($context));
    }

}