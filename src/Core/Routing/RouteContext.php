<?php

namespace Src\Core\Routing;

/**
 * Classe de contexte de routage - Singleton statique pour la gestion des routes
 * 
 * Cette classe finale fournit un conteneur statique pour stocker et récupérer
 * les informations de route courante et ses paramètres dans l'application.
 * 
 * Elle suit le pattern Registry et permet un accès global aux données de routage
 * sans avoir à les passer explicitement entre les différentes couches de l'application.
 * 
 * @package Src\Core\Routing
 */
final class RouteContext
{
    /**
     * Clé de la route courante au format "Controller@method"
     * 
     * @var string|null
     * @static
     * @access private
     */
    private static ?string $routeKey = null;

    /**
     * Paramètres de la route courante
     * 
     * @var array
     * @static
     * @access private
     */
    private static array $params = [];

    /**
     * Définit le contexte de route courant
     * 
     * Cette méthode initialise ou met à jour les informations de route
     * en cours d'exécution dans l'application.
     * 
     * @param string $routeKey La clé de route au format "Controller@method"
     * @param array $params Les paramètres de la route (par défaut: tableau vide)
     * 
     * @return void
     * 
     * @example
     * RouteContext::set('PostController@show', ['id' => 42, 'slug' => 'mon-article']);
     * 
     * @throws \InvalidArgumentException Si la clé de route n'est pas une chaîne valide
     */
    public static function set(string $routeKey, array $params = []): void
    {
        self::$routeKey = $routeKey;
        self::$params = $params;
    }

    /**
     * Récupère la clé de la route courante
     * 
     * Retourne la clé de route actuellement définie sous le format "Controller@method"
     * 
     * @return string|null La clé de route ou null si non définie
     * 
     * @example
     * $currentRoute = RouteContext::getRouteKey();
     * // Retourne: 'PostController@show'
     */
    public static function getRouteKey(): ?string
    {
        return self::$routeKey;
    }

    /**
     * Récupère les paramètres de la route courante
     * 
     * Retourne tous les paramètres associés à la route actuelle
     * 
     * @return array Tableau associatif des paramètres de route
     * 
     * @example
     * $params = RouteContext::getParams();
     * // Retourne: ['id' => 42, 'slug' => 'mon-article']
     */
    public static function getParams(): array
    {
        return self::$params;
    }
}
