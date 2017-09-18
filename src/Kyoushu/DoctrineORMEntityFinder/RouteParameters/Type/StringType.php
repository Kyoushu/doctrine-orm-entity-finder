<?php

namespace Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\FinderInterface;

class StringType implements TypeInterface
{

    /**
     * @param string|null $value
     * @return string
     */
    public function transform($value)
    {
        if($value === null) return FinderInterface::ROUTE_NULL_PLACEHOLDER;
        return (string)$value;
    }

    /**
     * @param string $value
     * @return null|string
     */
    public function reverseTransform($value)
    {
        if($value === FinderInterface::ROUTE_NULL_PLACEHOLDER) return null;
        return (string)$value;
    }

}