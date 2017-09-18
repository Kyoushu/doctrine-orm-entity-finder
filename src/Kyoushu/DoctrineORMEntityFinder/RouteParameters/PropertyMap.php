<?php

namespace Kyoushu\DoctrineORMEntityFinder\RouteParameters;

use Kyoushu\DoctrineORMEntityFinder\FinderInterface;
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
     * @param FinderInterface $finder
     * @return array
     */
    public function createRouteParameters(FinderInterface $finder)
    {
        $routeParameters = [];
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach($this->properties as $key => $type){
            $value = $propertyAccessor->getValue($finder, $key);
            $routeParameters[$key] = $type->transform($value);
        }

        return $routeParameters;
    }

    /**
     * @param FinderInterface $finder
     * @param array $routeParameters
     */
    public function applyRouteParameters(FinderInterface $finder, array $routeParameters)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach($this->properties as $key => $type){
            if(!isset($routeParameters[$key])) continue;
            $value = $routeParameters[$key];
            $propertyAccessor->setValue($finder, $key, $type->reverseTransform($value));
        }
    }

}