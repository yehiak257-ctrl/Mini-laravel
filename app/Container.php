<?php

namespace App;

use ReflectionMethod;

class Container
{
    public function call(object $object, string $method, array $params = [])
    {
        $reflection = new ReflectionMethod($object, $method);

        $args = [];

        foreach ($reflection->getParameters() as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if ($type !== null && !$type->isBuiltin()) {
                $class = $type->getName();
                $args[$name] = $this->make($class);
            } elseif (array_key_exists($name, $params)) {
                $args[$name] = $params[$name];
            }
        }

        return $object->$method(...$args);
    }
    public function make(string $class)
{
    $reflection = new \ReflectionClass($class);
    $constructor = $reflection->getConstructor();

    if ($constructor === null) {
        return new $class();
    }

    $args = [];

    foreach ($constructor->getParameters() as $parameter) {
        $type = $parameter->getType();

        if ($type !== null && !$type->isBuiltin()) {
            $args[] = $this->make($type->getName());
        }
    }

    return new $class(...$args);
}
}