<?php

namespace Src\Core\Resolver;

/**
 * Résolveur de titres de pages - Système centralisé de gestion des balises <title>
 * 
 * Cette classe fournit un mapping entre les routes de l'application et les titres
 * de pages correspondants à afficher dans les balises <title> du HTML.
 * 
 * Elle utilise le pattern Resolver et Factory pour générer des titres dynamiques
 * en fonction des paramètres de route et du contexte.
 * 
 * @package Src\Core\Resolver
 */
class PageTitleResolver
{
    /**
     * Résout et retourne le titre de page approprié selon la route et les paramètres
     * 
     * Cette méthode fait le mapping entre les routes de contrôleurs et les titres
     * de pages correspondants, avec support pour les titres dynamiques basés sur
     * les paramètres de route.
     * 
     * @param string $route La route au format "Controller@method"
     * @param array $params Tableau associatif des paramètres de la route
     * 
     * @return string|null Le titre de page formaté, "Mon Blog" par défaut
     * 
     * @example
     * // Titre statique
     * $title = PageTitleResolver::resolve('UserController@login');
     * // Retourne: "Connexion"
     * 
     * // Titre dynamique avec paramètres
     * $title = PageTitleResolver::resolve('PostController@show', ['id' => 42]);
     * // Retourne: "Article #42"
     * 
     * @throws \TypeError Si les types des paramètres sont incorrects
     */
    public static function resolve(string $route, array $params = []): ?string
    {
        switch ($route) {
            // Pages publiques
            case 'PostController@index':
                return "Accueil - Mon Blog";
            case 'PostController@articles':
                return "Tous les articles";
            case 'PostController@show':
                return isset($params['id'])
                    ? "Article #{$params['id']}"
                    : "Article - Mon Blog";

                // Authentification et utilisateur
            case 'UserController@login':
                return "Connexion";
            case 'UserController@register':
                return "Inscription";
            case 'UserController@profile':
                return "Mon Profil";

                // Gestion des commentaires
            case 'CommentController@list':
                return isset($params['articleId'])
                    ? "Commentaires de l'article #{$params['articleId']}"
                    : "Liste des commentaires";
            case 'CommentController@add':
                return "Ajouter un commentaire";
            case 'CommentController@update':
                return "Modifier un commentaire";
            case 'CommentController@delete':
                return "Supprimer un commentaire";

                // Administration
            case 'AdminController@dashboard':
                return "Tableau de bord";
            case 'PostController@create':
                return "Créer un article";
            case 'PostController@update':
                return "Modifier un article";
            case 'PostController@delete':
                return "Supprimer un article";
            case 'UserController@manageUsers':
                return "Gestion des utilisateurs";
            case 'CommentController@manageComments':
                return "Gestion des commentaires";

                // Pages d'erreur
            case 'ErrorController@unauthorized':
                return "Accès non autorisé";
            case 'ErrorController@notFound':
                return "Page introuvable";

                // Fallback sécurisé
            default:
                return "Mon Blog";
        }
    }
}
