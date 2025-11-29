<?php

/**
 * Configuration des routes de l'application
 * Chaque route est définie par :
 * - 'pattern'    : regex pour matcher l'URI
 * - 'controller' : contrôleur à instancier
 * - 'method'     : méthode du contrôleur
 * - 'middleware' : middlewares éventuels (auth, admin, etc.)
 */

return [

    // ----------------------------------------------------
    // ROUTES PUBLIQUES (LECTURE)
    // ----------------------------------------------------

    // Page d'accueil (/)
    [
        'http_method' => 'GET',
        'pattern' => '#^/$#',
        'controller' => 'PostController',
        'method' => 'accueil'
    ],

    // Liste complète des articles (/articles)
    [
        'http_method' => 'GET',
        'pattern' => '#^/articles$#',
        'controller' => 'PostController',
        'method' => 'articles'
    ],

    // Détail de l'article (/articles/12)
    [
        'http_method' => 'GET',
        'pattern' => '#^/articles/(?<id>\d+)$#',
        'controller' => 'PostController',
        'method' => 'show',
    ],


    // Authentification (UserController)
    [
        'pattern' => '#^/login$#',
        'controller' => 'UserController',
        'method' => 'login'
    ],
    [
        'pattern' => '#^/register$#',
        'controller' => 'UserController',
        'method' => 'register'
    ],
    [
        'pattern' => '#^/logout$#',
        'controller' => 'UserController',
        'method' => 'logout',
        'middleware' => ['auth']
    ],
    [
        'pattern' => '#^/profile$#',
        'controller' => 'UserController',
        'method' => 'profile',
        'middleware' => ['auth']
    ],





    // Commentaires (CommentController)
    [
        'pattern' => '#^/comments/list/(?<articleId>\d+)$#',
        'controller' => 'CommentController',
        'method' => 'list'
    ],
    [
        'pattern' => '#^/comments/add$#',
        'controller' => 'CommentController',
        'method' => 'add',
        'middleware' => ['auth']
    ],
    [
        'pattern' => '#^/comments/update/(?<id>\d+)$#',
        'controller' => 'CommentController',
        'method' => 'update',
        'middleware' => ['auth']
    ],
    [
        'pattern' => '#^/comments/delete/(?<id>\d+)$#',
        'controller' => 'CommentController',
        'method' => 'delete',
        'middleware' => ['auth', 'admin']
    ],





    // Administration
    [
        'pattern' => '#^/admin/dashboard$#',
        'controller' => 'AdminController',
        'method' => 'dashboard',
        'middleware' => ['auth', 'admin']
    ],
    [
        'pattern' => '#^/admin/manage_posts$#',
        'controller' => 'PostController',
        'method' => 'index',
        'middleware' => ['auth', 'admin']
    ],
    [
        'pattern' => '#^/admin/create_post$#',
        'controller' => 'PostController',
        'method' => 'create',
        'middleware' => ['auth', 'admin']
    ],
    [
        'pattern' => '#^/admin/update_post/(?<id>\d+)$#',
        'controller' => 'PostController',
        'method' => 'update',
        'middleware' => ['auth', 'admin']
    ],
    [
        'pattern' => '#^/admin/delete_post/(?<id>\d+)$#',
        'controller' => 'PostController',
        'method' => 'delete',
        'middleware' => ['auth', 'admin']
    ],
    [
        'pattern' => '#^/admin/manage_users$#',
        'controller' => 'UserController',
        'method' => 'profile', //Je met profile parce que je n'ai pas encore "manageUsers()", à créer si besoin
        'middleware' => ['auth', 'admin']
    ],
    [
        'pattern' => '#^/admin/manage_comments$#',
        'controller' => 'CommentController',
        'method' => 'list', //Je met list parce que je n'ai pas encore "manageComments()", à créer si besoin
        'middleware' => ['auth', 'admin']
    ],

    // Erreurs
    [
        'pattern' => '#^/unauthorized$#',
        'controller' => 'ErrorController',
        'method' => 'unauthorized',
    ],
    [
        'pattern' => '#^/404$#',
        'controller' => 'ErrorController',
        'method' => 'notFound',
    ],
];
