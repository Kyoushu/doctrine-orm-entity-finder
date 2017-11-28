<?php

namespace Kyoushu\DoctrineORMEntityFinder\RouteParameters;

use Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type\TypeInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyMap
{

    /**
     * @var TypeInterface[]
     */
    protected $properties;

    public function __construct()
    {
        $this->properties = [];
    }

    /**
     * @param string $name
     * @param TypeInterface $type
     * @return $this
     */
    public function addProperty($name, TypeInterface $type)
    {
        $this->properties[$name] = $type;
        return $this;
    }

    /**
     * @param string $name
     */
    public function removeProperty($name)
    {
        if(isset($this->properties[$name])){
            unset($this->properties[$name]);
        }
    }

    /**
     * @return TypeInterface[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param object $context
     * @return array
     */
    public function createRouteParameters($context)
    {
        $routeParameters = [];
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach($this->properties as $key => $type){
            $value = $propertyAccessor->getValue($context, $key);
            $routeParameters[$key] = $type->transform($value);
        }

        return $routeParameters;
    }

    /**
     * @param object $context
     * @param array $routeParameters
     */
    public function applyRouteParameters($context, array $routeParameters)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach($this->properties as $key => $type){
            if(!isset($routeParameters[$key])) continue;
            $value = $routeParameters[$key];
            $propertyAccessor->setValue($context, $key, $type->reverseTransform($value));
        }
    }

}