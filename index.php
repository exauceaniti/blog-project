<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Database\Database;
use App\Core\Container;
use App\Core\Routing\Router;

// 3. Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Définition des chemins globaux
define('ROOT_PATH', __DIR__ . '/');
define('VIEW_PATH', ROOT_PATH . 'Views');
define('TEMPLATE_PATH', ROOT_PATH . 'templates');
define('ASSET_PATH', ROOT_PATH . 'public/assets');

// 5. Initialisation du Container
$container = new Container();

// On enregistre l’instance PDO unique depuis Database
$container->set(\PDO::class, Database::getConnection());

// 6. Chargement des routes
$routesFile = ROOT_PATH . 'src/Core/Routing/Config/routes.php';

if (!file_exists($routesFile)) {
    die("Le fichier de configuration des routes est introuvable à : " . $routesFile);
}

// 7. Initialisation du Router
$router = new Router($routesFile, $container);

// 8. Dispatch (Exécution de la requête)
try {
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (Throwable $e) {
    http_response_code(500);
    echo "<h1>Erreur interne</h1>";
    echo "<p>{$e->getMessage()}</p>";
}
