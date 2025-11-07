<?php
//------ Fichier index.php ------


spl_autoload_register(function ($class) {
    // Convertit le namespace en chemin de fichier
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Autoload error: fichier introuvable pour la classe $class ($file)");
    }
});



session_start();
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/models/User.php';

$connexion = Connexion::getInstance();
$userModel = new User($connexion);

$url = $_SERVER['REQUEST_URI'];
$routes = require_once __DIR__ . '/Core/routes/routes.php';

$route = null;

foreach ($routes as $key => $rt) {
    if (preg_match("`$key`", $url)) {
        $route = $rt;
        break;
    }
}


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
