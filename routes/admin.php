<?php
require_once __DIR__ . '/../controllers/AdminController.php';

$adminController = new AdminController();

$page = $_GET['route'] ?? '';

// Route login admin accessible même si pas connecté
if ($page === 'admin/login') {
    require __DIR__ . '/../views/admin/login.php';
    exit;
}

// Vérification accès admin pour les autres pages
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?route=admin/login');
    exit;
}

// Pages admin autorisées
switch ($page) {
    case 'admin/dashboard':
        require __DIR__ . '/../views/admin/dashboard.php';
        break;
    case 'admin/manage_posts':
        require __DIR__ . '/../views/admin/manage_posts.php';
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
