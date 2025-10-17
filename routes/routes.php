<?php
return [
    // Routes publiques
    'public/home' => ['controller' => 'HomeController', 'method' => 'index'],
    'public/contact' => ['controller' => 'ContactController', 'method' => 'show'],

    // Routes admin
    'admin/login' => ['controller' => 'AdminController', 'method' => 'login'],
    'admin/dashboard' => ['controller' => 'AdminController', 'method' => 'dashboard'],
    'admin/manage_posts' => ['controller' => 'PostController', 'method' => 'managePosts'],
    'admin/manage_users' => ['controller' => 'UserController', 'method' => 'manageUsers'],
    'admin/manage_comments' => ['controller' => 'CommentController', 'method' => 'manageComments'],
    'admin/logout' => ['controller' => 'AdminController', 'method' => 'logout'],
];
