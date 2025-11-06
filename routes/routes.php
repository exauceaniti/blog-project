<?php
//------ Classe routes.php ------
// Fichier de configuration des routes de l'application


return [
    // Routes publiques
    '^/(home)?$' => ['controller' => 'HomeController', 'method' => 'index'],
    '^/articles$' => ['controller' => 'HomeController', 'method' => 'articles'],
    '^/article/(id:\d+)-(slug:.+)$' => ['controller' => 'HomeController', 'method' => 'showArticle'],
    '^/contact$' => ['controller' => 'ContactController', 'method' => 'show'],

    // Routes admin
    '^/admin/login$' => ['controller' => 'AdminController', 'method' => 'login'],
    '^/admin/dashboard' => ['controller' => 'AdminController', 'method' => 'dashboard'],

    //Ce qui concerne la gestion des articles
    '^/admin/manage_posts$' => ['controller' => 'PostController', 'method' => 'managePosts'],
    '^/admin/create_post$' => ['controller' => 'PostController', 'method' => 'create'],
    '^/admin/update_post$' => ['controller' => 'PostController', 'method' => 'update'],
    '^/admin/delete_post$' => ['controller' => 'PostController', 'method' => 'delete'],


    //Ce qui concerne la gestion des utilisateurs
    '^/admin/manage_users$' => ['controller' => 'UserController', 'method' => 'manageUsers'],

    //Ce qui concerne la gestion des commentaires
    '^admin/manage_comments$' => ['controller' => 'CommentController', 'method' => 'manageComments'],

    //Deconexion admin
    '^admin/logout$' => ['controller' => 'AdminController', 'method' => 'logout'],
];
