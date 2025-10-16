<?php
// ========================== routes/public.php ==========================

// S'assurer que la session est active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération de la route demandée (ex : public/home)
$route = $_GET['route'] ?? 'public/home';

$viewPath = __DIR__ . '/../views/public/' . str_ireplace('public/', '', $route) . '.php';

if (file_exists($viewPath)) {
    require_once $viewPath;

} else {
    // Si le fichier n'existe pas, on continue avec la logique de routage
    echo "La vue que vous rechercher est introuvable: " . htmlspecialchars($viewPath);
}

// Liste des routes publiques disponibles
$routes = [
    'public/home' => __DIR__ . '/../views/public/home.php',
    'public/login' => __DIR__ . '/../views/public/login.php',
    'public/register' => __DIR__ . '/../views/public/register.php',
    'public/profile' => __DIR__ . '/../views/public/profile.php', // si tu veux
];

// Vérifier l'état de connexion
$isLoggedIn = isset($_SESSION['user']);
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : null;

// 1 Si l’utilisateur est déjà connecté et tente d’aller sur login/register
if ($isLoggedIn && in_array($route, ['public/login', 'public/register'])) {
    // Redirection selon le rôle
    $redirect = ($userRole === 'admin')
        ? 'index.php?route=admin/dashboard'
        : 'index.php?route=public/home';

    header('Location: ' . $redirect);
    exit;
}

// 2 Si l’utilisateur n’est PAS connecté et tente d’accéder à une page protégée publique
$protectedPublicRoutes = [
    'public/profile',  // exemple : page de profil publique
];
if (!$isLoggedIn && in_array($route, $protectedPublicRoutes)) {
    $_SESSION['errors'] = ["Veuillez vous connecter pour accéder à cette page."];
    header('Location: index.php?route=public/login');
    exit;
}

// 3 Si la route existe → inclure la vue correspondante
if (isset($routes[$route])) {
    require_once $routes[$route];
    exit;
}

// 4 Si la route n'existe pas → afficher une 404
http_response_code(404);
echo "<h1>404 - Page introuvable</h1>";
echo "<p style='color: red;'>La page demandée n'existe pas dans la section publique.</p>";
exit;
