<?php

namespace App\Core\Routing;

class RouteCollection
{
    private array $routes = [];

    public function __construct(array $routesConfig)
    {
        foreach ($routesConfig as $route) {
            $this->add($route);
        }
    }

    private function add(array $route): void
    {
        $this->routes[] = [
            'pattern'    => $route['pattern'],
            'controller' => $route['controller'],
            'method'     => $route['method'],
            'middleware' => $route['middleware'] ?? [],
        ];
    }

    public function all(): array
    {
        return $this->routes;
    }

    public function match(string $uri, string $method): ?array
    {
        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [
                    'controller' => $route['controller'],
                    'method'     => $route['method'],
                    'middleware' => $route['middleware'],
                    'params'     => $params
                ];
            }
        }
        return null;
    }
}
