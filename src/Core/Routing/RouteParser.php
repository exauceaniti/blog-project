<?php

namespace App\Core\Routing;

class RouteParser
{
    /**
     * Transforme un chemin avec paramètres en regex
     * Exemple : /article/{id}-{slug} → #^/article/(?<id>\d+)-(?<slug>[^/]+)$#
     */
    public static function toRegex(string $path): string
    {
        $pattern = preg_replace('#\{id\}#', '(?P<id>\d+)', $path);
        $pattern = preg_replace('#\{slug\}#', '(?P<slug>[^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    /**
     * Extrait les paramètres depuis une URL
     */
    public static function extractParams(string $pattern, string $uri): array
    {
        if (preg_match($pattern, $uri, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }
        return [];
    }
}
