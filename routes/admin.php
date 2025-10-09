<?php
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/PostController.php';

$connexion = new Connexion();
$postController = new PostController($connexion);

$page = $_GET['route'] ?? 'admin/dashboard';

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
        require __DIR__ . '/../views/admin/creatPost.php';
        break;

    case 'admin/edit_post':
        require __DIR__ . '/../views/admin/editPost.php';
        break;

    case 'admin/delete_post':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $postController->delete($id);
            // Après suppression, on revient à la liste
            header('Location: index.php?route=admin/manage_posts');
            exit;
        } else {
            echo "<p>ID d'article manquant pour suppression.</p>";
        }
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
