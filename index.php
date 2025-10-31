<?php
//------ Fichier index.php ------

session_start();
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/models/User.php';

$connexion = new Connexion();
$userModel = new User($connexion);

// Récupération de la route
$route = $_GET['route'] ?? 'public/home';
$route = trim($route, '/');

// Sécurité admin
if (str_starts_with($route, 'admin/') && (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin')) {
    $route = 'admin/login';
}

// Charger les routes
$routes = require_once __DIR__ . '/routes/routes.php';

// Vérifier si la route existe
if (!array_key_exists($route, $routes)) {
    die("404 - Route non trouvée");
}

// Extraire le contrôleur et la méthode
$controllerName = $routes[$route]['controller'];
$methodName = $routes[$route]['method'];

// Charger le contrôleur
require_once __DIR__ . "/controllers/{$controllerName}.php";
$controller = new $controllerName($connexion);

// Appeler la méthode
if (method_exists($controller, $methodName)) {
    $controller->$methodName();
} else {
    die("Erreur : méthode '$methodName' introuvable dans '$controllerName'");
}
