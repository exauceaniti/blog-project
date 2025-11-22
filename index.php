<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/autoload.php';

use Src\Core\Routing\Router;
use Src\Core\Container;
use Src\Core\Database\Database;

// 1. Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Définition des chemins globaux
define('ROOT_PATH', __DIR__);
define('VIEW_PATH', ROOT_PATH . '/Views');
define('TEMPLATE_PATH', ROOT_PATH . '/templates');
define('ASSET_PATH', ROOT_PATH . '/assets');

// 3. Container
$container = new Container();

// On enregistre l’instance PDO unique depuis Database
$container->set(\PDO::class, Database::getConnection());

// 4. Chargement des routes
$routesFile = ROOT_PATH . '/src/Core/Routing/Config/routes.php';
if (!file_exists($routesFile)) {
    die("Le fichier de configuration des routes est introuvable.");
}

// 5. Router
$router = new Router($routesFile, $container);

// 6. Dispatch
try {
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (Throwable $e) {
    http_response_code(500);
    echo "<h1>Erreur interne</h1>";
    echo "<p>{$e->getMessage()}</p>";
}
