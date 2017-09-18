<?php

namespace Kyoushu\DoctrineORMEntityFinder\RouteParameters\Type;

interface TypeInterface
{

    /**
     * Transform a value into a string intended for use in a URL
     *
     * @param mixed $value
     * @return string|null
     */
    public function transform($value);

    /**
     * @param string|null $value
     * @return mixed
     */
    public function reverseTransform($value);

}