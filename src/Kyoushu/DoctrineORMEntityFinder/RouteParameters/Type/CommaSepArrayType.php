<?php

namespace Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\FinderInterface;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\Exception\TypeException;

class CommaSepArrayType implements TypeInterface
{

    public function transform($value)
    {
        if($value === null) return FinderInterface::ROUTE_NULL_PLACEHOLDER;
        if(!is_array($value)){
            throw new TypeException(sprintf(
                'Expected value to be array or NULL, %s given',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }
        if(count($value) === 0) return FinderInterface::ROUTE_NULL_PLACEHOLDER;
        return implode(',', $value);
    }

    public function reverseTransform($value)
    {
        if($value === FinderInterface::ROUTE_NULL_PLACEHOLDER) return [];
        return explode(',', $value);
    }


}