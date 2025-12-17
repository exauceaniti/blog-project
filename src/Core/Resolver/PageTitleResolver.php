<?php

namespace App\Core\Resolver;

/**
 * Résolveur de titres de pages - Système centralisé de gestion des balises <title>
 * 
 * Cette classe fournit un mapping entre les routes de l'application et les titres
 * de pages correspondants à afficher dans les balises <title> du HTML.
 * 
 * Elle utilise le pattern Resolver et Factory pour générer des titres dynamiques
 * en fonction des paramètres de route et du contexte.
 * 
 * @package App\Core\Resolver
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

                //titre de page qui contiens tout les aeticles 
            case 'PostController@articles':
                return "Tous les articles";

                //titre de page qui contiens les articles avec parametre.
            case 'PostController@show':
                return isset($params['id'])
                    ? "Article #{$params['id']}"
                    : "Article - Mon Blog";

                // Authentification et utilisateur
                //titre pour la page de connexion
            case 'UserController@login':
                return "Connexion";

                //titre pour la page d'inscription 
            case 'UserController@register':
                return "Inscription";

                //titre du profile de l'utilisateur.
            case 'UserController@profile':
                return "Mon Profil";

                // Gestion des commentaires
                //titre d'un commentaire avec parametre
            case 'CommentController@list':
                return isset($params['articleId'])
                    ? "Commentaires de l'article #{$params['articleId']}"
                    : "Liste des commentaires";

                //titre de la paage pour ajouter un commentaire. 
                //celui ci est une partie a revoir.
                //l'ajout du commentaire se fait juste avec de slabels.
            case 'CommentController@add':
                return "Ajouter un commentaire";

                //titre de page par defaut pour modifire un commentaire. 
            case 'CommentController@update':
                return "Modifier un commentaire";

                //titre par defaut pour supprimer un comentaire
            case 'CommentController@delete':
                return "Supprimer un commentaire";

                // Administration
                //titre du tableua de bord de l'admin 
            case 'AdminController@dashboard':
                return "Tableau de bord";

                //page pour cree un nouvel articles.
            case 'PostController@create':
                return "Créer un article";

                //par defaut pour modifier un articles.
            case 'PostController@update':
                return "Modifier un article";

                //par defaut pour supprimer un articles. 
                //mais je fairais ici juste que ca puisse se faire avec un simple clic sur le bouton.
            case 'PostController@delete':
                return "Supprimer un article";

                //Page pour la gestions des utilisateurs.
            case 'UserController@manageUsers':
                return "Gestion des utilisateurs";

                //Page pour la gestions des commentaires.
            case 'CommentController@manageComments':
                return "Gestion des commentaires";

                // Pages d'erreurs 
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
