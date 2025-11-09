<?php
/** Classe responsable de l'analyse des routes.
 * Elle vérifie si une URI correspond à une route définie
 * et extrait les paramètres dynamiques.
*/

namespace Core\Routing;

class RouteParser
{
    public static function match(string $uri, array $routes): ?array
    {
        foreach ($routes as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = self::extractParams($matches);
                return [
                    'controller' => $route['controller'],
                    'method' => $route['method'],
                    'params' => $params
                ];
            }
        }

        return null;
    }

    /**
     * Extrait les paramètres nommés des correspondances d'une expression régulière.
     * @param array $matches
     * @return array
     */
    private static function extractParams(array $matches): array
    {
        $params = [];
        foreach ($matches as $key => $value) {
            if (!is_int($key)) {
                $params[$key] = $value;
            }
        }
        return $params;
    }
}
