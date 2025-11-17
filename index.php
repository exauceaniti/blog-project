    <?php
    /**
     * Point d'entrée principal de l'application
     * -----------------------------------------
     * - Charge l'autoload de Composer (PSR-4).
     * - Initialise la session.
     * - Configure les constantes de chemin.
     * - Instancie le Router et dispatch la requête.
     */

    declare(strict_types=1);

    // Autoload Composer
    require_once __DIR__ . '/vendor/autoload.php';

    // Démarrage de la session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Définition des chemins globaux
    define('ROOT_PATH', __DIR__);
    define('VIEW_PATH', ROOT_PATH . '/Views');
    define('TEMPLATE_PATH', ROOT_PATH . '/templates');
    define('ASSET_PATH', ROOT_PATH . '/assets');

    // Import des classes nécessaires
    use Src\Core\Routing\Router;

    // 5 Chargement des routes
    $routesFile = ROOT_PATH . '/src/Core/Routing/Config/routes.php';
    if (!file_exists($routesFile)) {
        die("Le fichier de configuration des routes est introuvable.");
    }

    // Instanciation du Router
    $router = new Router($routesFile);

    // Dispatch de la requête
    try {
        $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    } catch (Exception $e) {
        // Gestion des erreurs globales
        http_response_code(500);
        echo "<h1>Erreur interne</h1>";
        echo "<p>{$e->getMessage()}</p>";
    }
