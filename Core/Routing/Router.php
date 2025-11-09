<?php
/** Classe responsable de la gestion du routage.
 * Elle utilise une collection de routes et un analyseur de routes
 * pour dispatcher les requêtes vers les contrôleurs appropriés.
*/

namespace Core\Routing;

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

        $this->call($match['controller'], $match['method'], $match['params']);
    }

    
    /**
     * Appelle la méthode d'un contrôleur avec les paramètres donnés.
     * Summary of call
     * @param string $controllerName
     * @param string $method
     * @param array $params
     * @throws \Exception
     * @return void
     */
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
