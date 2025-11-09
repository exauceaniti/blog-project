<?php
/** Classe représentant une collection de routes. 
 * Elle charge les définitions de routes à partir d'un fichier de configuration
 * et fournit une méthode pour récupérer toutes les routes.
*/

namespace Core\Routing;

class RouteCollection
{
    private array $routes = [];

    public function __construct(string $configPath)
    {
        $this->routes = require $configPath;
    }

    public function all(): array
    {
        return $this->routes;
    }
}
