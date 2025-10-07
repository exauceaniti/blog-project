<?php
$page = $_GET['route'] ?? 'home';

switch ($page) {
    case 'home':
        require __DIR__ . '/../views/public/home.php';
        break;

    case 'article':
        require __DIR__ . '/../views/public/article.php';
        break;

    case 'login':
        require __DIR__ . '/../views/public/login.php';
        break;

    case 'register':
        require __DIR__ . '/../views/public/register.php';
        break;

    default:
        echo "<h2>Page publique introuvable : $page</h2>";
        break;
}
