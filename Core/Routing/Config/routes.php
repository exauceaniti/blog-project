<?php
/**------ Fichier de configuration des routes de l'application ------
 * Chaque route est définie par un tableau associatif contenant :
 * - 'pattern'    : une expression régulière pour faire correspondre l'URI
 * - 'controller' : le nom du contrôleur à instancier
 * - 'method'     : la méthode du contrôleur à appeler
 */

return [
    // Routes publiques
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
        'method' => 'show'
    ],

    // Routes admin
    [
        'pattern' => '#^/admin/login$#',
        'controller' => 'AdminController',
        'method' => 'login'
    ],
    [
        'pattern' => '#^/admin/dashboard$#',
        'controller' => 'AdminController',
        'method' => 'dashboard'
    ],

    // Gestion des articles
    [
        'pattern' => '#^/admin/manage_posts$#',
        'controller' => 'PostController',
        'method' => 'managePosts'
    ],
    [
        'pattern' => '#^/admin/create_post$#',
        'controller' => 'PostController',
        'method' => 'create'
    ],
    [
        'pattern' => '#^/admin/update_post$#',
        'controller' => 'PostController',
        'method' => 'update'
    ],
    [
        'pattern' => '#^/admin/delete_post$#',
        'controller' => 'PostController',
        'method' => 'delete'
    ],

    // Gestion des utilisateurs
    [
        'pattern' => '#^/admin/manage_users$#',
        'controller' => 'UserController',
        'method' => 'manageUsers'
    ],

    // Gestion des commentaires
    [
        'pattern' => '#^/admin/manage_comments$#',
        'controller' => 'CommentController',
        'method' => 'manageComments'
    ],

    // Déconnexion
    [
        'pattern' => '#^/admin/logout$#',
        'controller' => 'AdminController',
        'method' => 'logout'
    ]
];
