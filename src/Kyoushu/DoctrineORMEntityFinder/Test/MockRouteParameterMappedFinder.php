<?php

namespace Kyoushu;

use Kyoushu\DoctrineORMEntityFinder\RouteParameters\PropertyMap;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\BoolType;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\CommaSepArrayType;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\DateTimeType;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\StringType;
use Kyoushu\DoctrineORMEntityFinder\Test\MockFinder;

class MockRouteParameterMappedFinder extends MockFinder
{

    /**
     * @var bool|null
     */
    protected $published;

    /**
     * @var string[]
     */
    protected $attributes;

    public function __construct()
    {
        $this->attributes = [];
    }

    public function createRouteParameterMap()
    {
        return (new PropertyMap())
            ->addProperty('name',       new StringType())
            ->addProperty('created',     new DateTimeType())
            ->addProperty('published',  new BoolType())
            ->addProperty('attributes', new CommaSepArrayType())
        ;
    }

    /**
     * @return bool|null
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param bool|null $published
     * @return $this
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string[] $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

}