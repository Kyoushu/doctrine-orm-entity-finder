<?php

namespace Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type;

use Kyoushu\DoctrineORMEntityFinder\FinderInterface;
use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\Exception\TypeException;

class DateTimeType implements TypeInterface
{

    /**
     * @param null|\DateTime $value
     * @return string
     * @throws TypeException
     */
    public function transform($value)
    {
        if($value === null) return FinderInterface::ROUTE_NULL_PLACEHOLDER;
        if(!$value instanceof \DateTime){
            throw new TypeException(sprintf(
                'Expected value to be NULL or instance of \DateTime, %s given',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }
        return $value->format('c');
    }

    /**
     * @param null|string $value
     * @return \DateTime|null
     */
    public function reverseTransform($value)
    {
        if($value === FinderInterface::ROUTE_NULL_PLACEHOLDER) return null;
        return new \DateTime($value);
    }


}