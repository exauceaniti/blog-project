<?php

namespace Src\Core\Routing;

use Src\Core\Routing\RouteCollection;
// use Src\Core\Routing\RouteParser;
use Src\Core\Middleware\AuthMiddleware;

class Router
{
    private RouteCollection $collection;

    public function __construct(string $configPath)
    {
        $this->collection = new RouteCollection(require $configPath);
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $match = $this->collection->match($uri, $method);

        if (!$match) {
            http_response_code(404);
            $this->call('ErrorController', 'notFound', []);
            return;
        }

        // Vérification des middlewares
        foreach ($match['middleware'] as $mw) {
            if ($mw === 'auth') {
                AuthMiddleware::requireAuth();
            }
            if ($mw === 'admin') {
                AuthMiddleware::requireAdmin();
            }
            if ($mw === 'user') {
                AuthMiddleware::requireUser();
            }
        }

        $this->call($match['controller'], $match['method'], $match['params']);
    }

    private function call(string $controllerName, string $method, array $params): void
    {
        $controllerClass = "Src\\Controller\\" . $controllerName;

        if (!class_exists($controllerClass)) {
            throw new \Exception("Contrôleur $controllerClass introuvable.");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new \Exception("Méthode $method introuvable dans $controllerClass.");
        }

        call_user_func_array([$controller, $method], $params);
    }
}
