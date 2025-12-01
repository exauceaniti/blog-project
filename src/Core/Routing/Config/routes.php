<?php

/**
 * Configuration complète des routes de l'application (MVC)
 * Chaque route est définie par :
 * - 'http_method' : Méthode HTTP (GET, POST, etc.). ESSENTIEL pour le CRUD.
 * - 'pattern'     : Regex pour matcher l'URI.
 * - 'controller'  : Contrôleur à instancier.
 * - 'method'      : Méthode du contrôleur à exécuter.
 * - 'middleware'  : Middlewares éventuels (auth, admin, etc.).
 */

return [

    // =================================================================
    // 🌐 ROUTES PUBLIQUES (LECTURE)
    // =================================================================

    // Page d'accueil : Affiche les 5 derniers articles (méthode optimisée)
    [
        'http_method' => 'GET',
        'pattern' => '#^/$#',
        'controller' => 'HomeController',
        'method' => 'accueil'
    ],

    // Liste complète des articles : Affiche tous les articles
    [
        'http_method' => 'GET',
        'pattern' => '#^/articles$#',
        'controller' => 'HomeController',
        'method' => 'articles'
    ],

    // Détail d'un article unique (ex: /articles/12)
    [
        'http_method' => 'GET',
        'pattern' => '#^/articles/(?<id>\d+)$#',
        'controller' => 'HomeController',
        'method' => 'show',
    ],

    // =================================================================
    // 🚪 AUTHENTIFICATION (USER)
    // =================================================================

    // Affichage et traitement du formulaire de connexion
    [
        'http_method' => 'GET|POST', // Permet de gérer GET (afficher) et POST (soumettre)
        'pattern' => '#^/login$#',
        'controller' => 'UserController',
        'method' => 'login'
    ],

    // Affichage et traitement du formulaire d'inscription
    [
        'http_method' => 'GET|POST',
        'pattern' => '#^/register$#',
        'controller' => 'UserController',
        'method' => 'register'
    ],

    // Déconnexion
    [
        'http_method' => 'GET',
        'pattern' => '#^/logout$#',
        'controller' => 'UserController',
        'method' => 'logout',
        'middleware' => ['auth']
    ],

    // Profil utilisateur
    [
        'http_method' => 'GET',
        'pattern' => '#^/profile$#',
        'controller' => 'UserController',
        'method' => 'profile',
        'middleware' => ['auth']
    ],

    // =================================================================
    // 💬 COMMENTAIRES (CRUD)
    // =================================================================

    // Ajout d'un commentaire (via formulaire POST)
    [
        'http_method' => 'POST',
        'pattern' => '#^/comments/add$#',
        'controller' => 'CommentController',
        'method' => 'add',
        'middleware' => ['auth']
    ],

    // Mise à jour d'un commentaire (GET pour form, POST pour traitement)
    [
        'http_method' => 'GET|POST',
        'pattern' => '#^/comments/update/(?<id>\d+)$#',
        'controller' => 'CommentController',
        'method' => 'update',
        'middleware' => ['auth']
    ],

    // Suppression d'un commentaire (Action POST pour sécurité)
    [
        'http_method' => 'POST',
        'pattern' => '#^/comments/delete/(?<id>\d+)$#',
        'controller' => 'CommentController',
        'method' => 'delete',
        'middleware' => ['auth', 'admin'] // Admin peut supprimer n'importe quel commentaire
    ],

    // =================================================================
    // 🛡️ ADMINISTRATION (POSTS CRUD - NOUVEAUX CONVENTIONS)
    // =================================================================

    // Dashboard Admin
    [
        'http_method' => 'GET',
        'pattern' => '#^/admin/dashboard$#',
        'controller' => 'AdminController',
        'method' => 'dashboard',
        'middleware' => ['auth', 'admin']
    ],

    // AFFICHER la liste de gestion des articles pour l'admin
    [
        'http_method' => 'GET',
        'pattern' => '#^/admin/posts$#',
        'controller' => 'AdminController',
        'method' => 'managePosts',
        'middleware' => ['auth', 'admin']
    ],

    // AFFICHER le formulaire de création d'un article
    [
        'http_method' => 'GET',
        'pattern' => '#^/admin/ajouter$#',
        'controller' => 'AdminController',
        'method' => 'ajouterArticle',
        'middleware' => ['auth', 'admin']
    ],

    // TRAITER la soumission du formulaire de création (Action POST)
    [
        'http_method' => 'POST',
        'pattern' => '#^/post/create$#', // URL de traitement standard
        'controller' => 'PostController',
        'method' => 'create',
        'middleware' => ['auth', 'admin']
    ],



    // AFFICHER le formulaire de modification
    [
        'http_method' => 'GET',
        'pattern' => '#^/admin/posts/edit/(?<id>\d+)$#',
        'controller' => 'PostController',
        'method' => 'displayUpdateForm',
        'middleware' => ['auth', 'admin']
    ],

    // TRAITER la modification (Action POST ou PUT)
    [
        'http_method' => 'POST',
        'pattern' => '#^/post/update/(?<id>\d+)$#', // URL de traitement standard
        'controller' => 'PostController',
        'method' => 'update',
        'middleware' => ['auth', 'admin']
    ],

    // TRAITER la suppression (Action POST ou DELETE)
    [
        'http_method' => 'POST',
        'pattern' => '#^/post/delete/(?<id>\d+)$#', // URL de traitement standard
        'controller' => 'PostController',
        'method' => 'delete',
        'middleware' => ['auth', 'admin']
    ],

    // Gestion des Utilisateurs (à implémenter)
    [
        'http_method' => 'GET',
        'pattern' => '#^/admin/users$#',
        'controller' => 'UserController',
        'method' => 'manageUsers', // Nécessite la création de cette méthode
        'middleware' => ['auth', 'admin']
    ],

    // Gestion des Commentaires (à implémenter)
    [
        'http_method' => 'GET',
        'pattern' => '#^/admin/comments$#',
        'controller' => 'CommentController',
        'method' => 'manageComments', // Nécessite la création de cette méthode
        'middleware' => ['auth', 'admin']
    ],

    // =================================================================
    // ERREURS
    // =================================================================

    [
        'http_method' => 'GET',
        'pattern' => '#^/unauthorized$#',
        'controller' => 'ErrorController',
        'method' => 'unauthorized',
    ],
    [
        'http_method' => 'GET',
        'pattern' => '#^/404$#',
        'controller' => 'ErrorController',
        'method' => 'notFound',
    ],
];
