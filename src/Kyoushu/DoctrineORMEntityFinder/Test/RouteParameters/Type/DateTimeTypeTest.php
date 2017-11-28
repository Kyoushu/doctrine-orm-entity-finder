<?php

namespace Kyoushu\DoctrineORMEntityFinder\Test\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\DateTimeType;

class DateTimeTypeTest extends TypeTestCase
{

    public function testTransform()
    {
        $type = new DateTimeType();

        $this->assertEquals('-', $type->transform(null));
        $this->assertEquals('2017-01-01T12:15:30+00:00', $type->transform(new \DateTime('2017-01-01 12:15:30')));
    }

    public function testReverseTransform()
    {
        $type = new DateTimeType();

        $this->assertEquals(null, $type->reverseTransform('-'));

        $datetime = $type->reverseTransform('2017-01-01T12:15:30+00:00');
        $this->assertEquals('2017-01-01', $datetime->format('Y-m-d'));
        $this->assertEquals('12:15:30', $datetime->format('H:i:s'));
    }

    public function testAltFormats()
    {
        $type = (new DateTimeType())->setFormat('Y-m-d');
        $this->assertEquals('2017-01-02', $type->transform(new \DateTime('2017-01-02T12:32:12+00:00')));
        $this->assertEquals('2017-01-02', $type->reverseTransform('2017-01-02')->format('Y-m-d'));

        $type = (new DateTimeType())->setFormat('d/m/Y');
        $this->assertEquals('02/01/2017', $type->transform(new \DateTime('2017-01-02T12:32:12+00:00')));
        $this->assertEquals('2017-01-02', $type->reverseTransform('02/01/2017')->format('Y-m-d'));

        $type = (new DateTimeType())->setFormat('m/d/Y');
        $this->assertEquals('01/02/2017', $type->transform(new \DateTime('2017-01-02T12:32:12+00:00')));
        $this->assertEquals('2017-01-02', $type->reverseTransform('01/02/2017')->format('Y-m-d'));
    }

}