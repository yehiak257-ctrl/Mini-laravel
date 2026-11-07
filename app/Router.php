<?php

namespace App;

class Router
{
    protected array $routes = [];

    public function get(string $uri, callable|array $action)
    {
        $this->routes[$uri] = $action;
    }

    public function dispatch(string $uri)
    {
        foreach ($this->routes as $registered => $action) {
            $params = $this->extractParams($registered, $uri);

            if ($params === false) {
                continue;
            }

            if (is_array($action)) {
                [$class, $method] = $action;

                $container = new Container();

                $controller = $container->make($class);

                return $container->call($controller, $method, $params);
            }

            return $action(...$params);
        }

        return '404 Not Found';
    }

    protected function extractParams(string $registered, string $requested)
    {
        $registeredParts = explode('/', trim($registered, '/'));
        $requestedParts  = explode('/', trim($requested, '/'));

        if (count($registeredParts) !== count($requestedParts)) {
            return false;
        }

        $params = [];

        foreach ($registeredParts as $index => $part) {
            if (str_starts_with($part, '{')) {
                $params[trim($part, '{}')] = $requestedParts[$index];
            } elseif ($part !== $requestedParts[$index]) {
                return false;
            }
        }

        return $params;
    }
}