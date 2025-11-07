<?php
//------ Fichier index.php ------

session_start();
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/models/User.php';

$connexion = Connexion::getInstance();
$userModel = new User($connexion);

$url = $_SERVER['REQUEST_URI'];

$routes = require_once __DIR__ . '/routes/routes.php';

$route = null;

foreach ($routes as $key => $rt) {
    if (preg_match("`$key`", $url)) {
        $route = $rt;
        break;
    }
}

//Cette partie sera enlever c'est juste pour faire des testes approfondie sur github

if ($route == null) {
     die("404 - Route non trouvée: $url");
}


// Extraire le contrôleur et la méthode
$controllerName = $route['controller'];
$methodName = $route['method'];

// Charger le contrôleur
require_once __DIR__ . "/controllers/{$controllerName}.php";
$controller = new $controllerName($connexion);

// Appeler la méthode
if (method_exists($controller, $methodName)) {
    $controller->$methodName();
} else {
    die("Erreur : méthode '$methodName' introuvable dans '$controllerName'");
}
