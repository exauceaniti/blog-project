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

    // 🌍 Routes publiques
    [
        'pattern' => '#^/(home)?$#',
        'controller' => 'HomeController',
        'method' => 'index'
    ],
    [
        'pattern' => '#^/articles$#',
        'controller' => 'HomeController',
        'method' => 'articles'
    ],
    [
        'pattern' => '#^/article/(?<id>\d+)-(?<slug>[^/]+)$#',
        'controller' => 'HomeController',
        'method' => 'showArticle'
    ],
    [
        'pattern' => '#^/contact$#',
        'controller' => 'ContactController',
        'method' => 'show',
        'middleware' => ['auth']
    ],

    // 🔐 Authentification
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
        'pattern' => '#^/user/profile$#',
        'controller' => 'UserController',
        'method' => 'profile',
        'middleware' => ['auth']
    ],

    // ⚙️ Administration
    [
        'pattern' => '#^/admin/dashboard$#',
        'controller' => 'AdminController',
        'method' => 'dashboard',
        'middleware' => ['auth','admin']
    ],
    [
        'pattern' => '#^/admin/manage_posts$#',
        'controller' => 'PostController',
        'method' => 'managePosts',
        'middleware' => ['auth','admin']
    ],
    [
        'pattern' => '#^/admin/create_post$#',
        'controller' => 'PostController',
        'method' => 'create',
        'middleware' => ['auth','admin']
    ],
    [
        'pattern' => '#^/admin/update_post/(?<id>\d+)$#',
        'controller' => 'PostController',
        'method' => 'update',
        'middleware' => ['auth','admin']
    ],
    [
        'pattern' => '#^/admin/delete_post/(?<id>\d+)$#',
        'controller' => 'PostController',
        'method' => 'delete',
        'middleware' => ['auth','admin']
    ],
    [
        'pattern' => '#^/admin/manage_users$#',
        'controller' => 'UserController',
        'method' => 'manageUsers',
        'middleware' => ['auth','admin']
    ],
    [
        'pattern' => '#^/admin/manage_comments$#',
        'controller' => 'CommentController',
        'method' => 'manageComments',
        'middleware' => ['auth','admin']
    ],

    // 🚫 Erreurs
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
