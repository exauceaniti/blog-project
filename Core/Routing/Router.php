<?php

namespace Core\Routing;

/**
 * Classe Router
 * 
 * Gère le routage des requêtes HTTP vers les contrôleurs appropriés
 * Les routes recoivent l'URI, recherche la route correspondante, verifie le Middleware,
 * puis invoque le contrôleur et la méthode associés.
 */

class Router
{
    private RouteCollection $collection;

    public function __construct(string $configPath)
    {
        $this->collection = new RouteCollection($configPath);
    }

    public function dispatch(string $uri): void
    {
        $routes = $this->collection->all();
        $match = RouteParser::match($uri, $routes);

        if (!$match) {
            http_response_code(404);
            echo "404 - Route non trouvée";
            return;
        }

        // Vérification des middleware
        if (isset($match['middleware']) && is_array($match['middleware'])) {
            foreach ($match['middleware'] as $middleware) {
                $middlewareClass = "Core\\Middleware\\" . ucfirst($middleware) . "Middleware";
                if (class_exists($middlewareClass) && method_exists($middlewareClass, 'handle')) {
                    $middlewareClass::handle();
                }
            }
        }

        $this->call($match['controller'], $match['method'], $match['params']);
    }

    private function call(string $controllerName, string $method, array $params): void
    {
        $controllerClass = "\\controllers\\" . $controllerName;

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
