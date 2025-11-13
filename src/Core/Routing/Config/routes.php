<?php
/**------ Fichier de configuration des routes de l'application ------
 * Chaque route est définie par un tableau associatif contenant :
 * - 'pattern'    : une expression régulière pour faire correspondre l'URI
 * - 'controller' : le nom du contrôleur à instancier
 * - 'method'     : la méthode du contrôleur à appeler
 */

return [
    // Routes qui menent a la page publics pour tout utilisateur connecter ou non connecter.
    [
        'pattern' => '#^/(home)?$#',
        'controller' => 'HomeController',
        'method' => 'index'
    ],

    //Routes qui menent a la page des articles pour tout utilisateur connecter ou non connecter.
    [
        'pattern' => '#^/articles$#',
        'controller' => 'HomeController',
        'method' => 'articles'
    ],

    // Route pour afficher un article spécifique avec des parametres dynamiques
    [
        'pattern' => '#^/article/(?<id>\d+)-(?<slug>[^/]+)$#',
        'controller' => 'HomeController',
        'method' => 'showArticle'
    ],

    // Route pour la page de contact accessible uniquement aux utilisateurs authentifiés
    [
        'pattern' => '#^/contact$#',
        'controller' => 'ContactController',
        'method' => 'show',
        'middleware' => ['auth']
    ],

    // Authentification publique
    [
        'pattern' => '#^/public/login$#',
        'controller' => 'UserController',
        'method' => 'login'
    ],
    [
        'pattern' => '#^/public/register$#',
        'controller' => 'UserController',
        'method' => 'register'
    ],
    [
        'pattern' => '#^/public/logout$#',
        'controller' => 'UserController',
        'method' => 'logout',
        'middleware' => ['auth']
    ],

    //Methode que je vais faire venir apres. 
    // [
    //     'pattern' => '#^/user/profile$#',
    //     'controller' => 'UserController',
    //     'method' => 'profile',
    //     'middleware' => ['auth']
    // ],


    // Tableau des routes admin protégées par le middleware AdminMiddleware
    [
        'pattern' => '#^/admin/dashboard$#',
        'controller' => 'AdminController',
        'method' => 'dashboard',
        'middleware' => ['auth','admin']
    ],

    // Routes pour la gestion des articles uniquement pour l'administrateur
    [
        'pattern' => '#^/admin/manage_posts$#',
        'controller' => 'PostController',
        'method' => 'managePosts',
        'middleware' => ['auth','admin']
    ],

    // Routes pur cree un nouvelle articles, uniquement pour l'adin dans un premier temps
    [
        'pattern' => '#^/admin/create_post$#',
        'controller' => 'PostController',
        'method' => 'create',
        'middleware' => ['auth','admin']
    ],

    // Routes pour mettre a jour un article, uniquement pour l'administrateur pour le premier temps
    [
        'pattern' => '#^/admin/update_post$#',
        'controller' => 'PostController',
        'method' => 'update',
        'middleware' => ['auth','admin']
    ],

    // Routes pour supprimer un article, uniquement pour l'administrateur pour le premier temps
    [
        'pattern' => '#^/admin/delete_post$#',
        'controller' => 'PostController',
        'method' => 'delete',
        'middleware' => ['auth','admin']
    ],

    // Routes qui menent a la gestion des utilisateurs uniquement pour l'administrateur pour le premier temps
    [
        'pattern' => '#^/admin/manage_users$#',
        'controller' => 'UserController',
        'method' => 'manageUsers',
        'middleware' => ['auth','admin']
    ],

    // Routes pour la gestion des commentaires uniquement pour l'administrateur pour le premier temps
    [
        'pattern' => '#^/admin/manage_comments$#',
        'controller' => 'CommentController',
        'method' => 'manageComments',
        'middleware' => ['auth','admin']
    ],

    // ROutes pour afficher un message d'acces refuser ou non autoriser
    [
        'pattern' => '#^/unauthorized$#',
        'controller' => 'ErrorController',
        'method' => 'unauthorized',
    ],


];
