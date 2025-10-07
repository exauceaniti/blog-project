<?php
$page = $_GET['route'] ?? 'home';

switch ($page) {
    case 'admin/login':
        require __DIR__ . '/../views/admin/login.php';
        exit;

    case 'admin/dashboard':
        require __DIR__ . '/../views/admin/dashboard.php';
        break;

    case 'admin/manage_posts':
        require __DIR__ . '/../views/admin/manage_posts.php';
        break;

    case 'admin/create_post':
        require __DIR__ . '/../views/admin/createPost.php';
        break;

    case 'admin/manage_comments':
        require __DIR__ . '/../views/admin/manage_comments.php';
        break;

    case 'admin/manage_users':
        require __DIR__ . '/../views/admin/manage_users.php';
        break;

    case 'admin/logout':
        (new AdminController())->logout();
        break;

    default:
        echo "<h2>Page admin introuvable : $page</h2>";
        break;
}
