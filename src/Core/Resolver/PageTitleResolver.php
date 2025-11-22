<?php

namespace Src\Core\Resolver;

use Src\Core\Lang\MessageBag;

/**
 * Classe PageTitleResolver
 * ------------------------
 * Résout automatiquement les titres de page basés sur les routes/URLs.
 * 
 * Cette classe tente de déterminer le titre d'une page en fonction :
 * 1. De la route actuelle via MessageBag (traductions)
 * 2. De patterns d'URLs dynamiques (articles/, user/profile/)
 * 3. D'une valeur par défaut si aucune correspondance
 * 
 * @package Src\Core\Resolver
 */
class PageTitleResolver
{
    /**
     * Résout le titre de la page pour une route donnée
     *
     * Ordre de priorité :
     * 1. MessageBag avec la clé "titles.{route}"
     * 2. Patterns d'URLs dynamiques (articles/, user/profile/)
     * 3. Valeur par défaut "Mon Blog"
     *
     * @param string $route La route/URL à résoudre (ex: "/articles", "/user/profile/123")
     * @return string Le titre résolu pour la page
     * 
     * @example
     * PageTitleResolver::resolve('/'); // → "Accueil - Mon Blog" (si dans MessageBag)
     * PageTitleResolver::resolve('/articles/42'); // → "Article #42"
     * PageTitleResolver::resolve('/contact'); // → "Contact - Mon Blog" (si dans MessageBag)
     */
    public static function resolve(string $route): string
    {
        // Nettoyage de la route : suppression des query params et slash final
        $cleanRoute = strtok($route, '?');
        $cleanRoute = $cleanRoute === '/' ? '/' : rtrim($cleanRoute, '/');

        // === ÉTAPE 1 : Recherche dans MessageBag ===
        $title = MessageBag::get("titles.$cleanRoute");


        // Vérification si le titre a été trouvé dans MessageBag
        // MessageBag retourne "Message inconnu" quand la clé n'existe pas
        if ($title && $title !== "titles.$cleanRoute" && !str_contains($title, "Inconue")) {
            return $title;
        }
        // === ÉTAPE 2 : Routes dynamiques (fallback) ===

        // Pattern: /articles/123 → "Article #123"
        if (preg_match('#^/articles/(\d+)$#', $cleanRoute, $matches)) {
            return "Article #" . $matches[1];
        }

        // Pattern: /user/profile/123 → "Profil utilisateur #123"
        if (preg_match('#^/user/profile/(\d+)$#', $cleanRoute, $matches)) {
            return "Profil utilisateur #" . $matches[1];
        }

        // === ÉTAPE 3 : Valeur par défaut ===
        return "Mon Blog";
    }
}
