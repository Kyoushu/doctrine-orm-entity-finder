<?php

namespace Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\FinderInterface;

class BoolType implements TypeInterface
{

    public function transform($value)
    {
        if(!$value) return FinderInterface::ROUTE_NULL_PLACEHOLDER;
        return '1';
    }

    public function reverseTransform($value)
    {
        if($value === FinderInterface::ROUTE_NULL_PLACEHOLDER) return null;
        if(!$value) return null;
        return true;
    }

}