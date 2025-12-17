<?php

namespace App\Core\Routing;

use App\Core\Container;
use App\Core\Middleware\AuthMiddleware;
use App\Core\Routing\RouteContext;

/**
 * Routeur principal de l'application - Gestionnaire du cycle de requête HTTP
 * 
 * Cette classe est le cœur du système de routage. Elle orchestre :
 * - La résolution d'URI vers des contrôleurs
 * - L'exécution des middlewares
 * - La gestion du contexte de route
 * - L'injection de dépendances via Container
 * 
 * @package App\Core\Routing
 */
class Router
{
    /**
     * Collection des routes configurées
     * 
     * @var RouteCollection
     * @access private
     */
    private RouteCollection $collection;

    /**
     * Container d'injection de dépendances
     * 
     * @var Container
     * @access private
     */
    private Container $container;

    /**
     * Constructeur du routeur
     * 
     * Initialise la collection de routes et le container DI
     * 
     * @param string $configPath Chemin vers le fichier de configuration des routes
     * @param Container|null $container Instance du container (création auto si null)
     * 
     * @example
     * $router = new Router(__DIR__ . '/../../config/routes.php');
     */
    public function __construct(string $configPath, ?Container $container = null)
    {
        // Charge la configuration des routes et initialise la collection
        $this->collection = new RouteCollection(require $configPath);

        // Utilise le container fourni ou en crée un nouveau
        $this->container = $container ?? new Container();

        // Configure les bindings DI si nécessaires
        // $this->bootBindings();
    }

    /**
     * Point d'entrée principal - Dispatch une requête HTTP
     * 
     * Cette méthode orchestre tout le cycle de traitement :
     * 1. Résolution de l'URI vers une route
     * 2. Vérification des middlewares de sécurité
     * 3. Configuration du contexte global
     * 4. Exécution du contrôleur cible
     * 
     * @param string $uri URI de la requête (ex: "/posts/42")
     * @param string $method Méthode HTTP (GET, POST, etc.)
     * 
     * @return void
     * 
     * @example
     * $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
     * 
     * @throws \RuntimeException Si le contrôleur ou méthode est introuvable
     */
    public function dispatch(string $uri, string $method): void
    {
        // ÉTAPE 1: Extraction et matching du chemin
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $match = $this->collection->match($path, $method);

        // Si aucune route ne match → 404
        if (!$match) {
            http_response_code(404);
            $this->call('ErrorController', 'notFound', []);
            return;
        }

        // ÉTAPE 2: Execution des middlewares de sécurité
        $this->executeMiddlewares($match['middleware']);

        // ÉTAPE 3: Configuration du contexte global de route
        $routeKey = $match['controller'] . '@' . $match['method'];
        RouteContext::set($routeKey, $match['params'] ?? []);

        // ÉTAPE 4: Appel du contrôleur avec ses paramètres
        $this->call($match['controller'], $match['method'], $match['params']);
    }

    /**
     * Exécute la chaîne de middlewares de sécurité
     * 
     * @param array $middlewares Liste des middlewares à exécuter
     * 
     * @return void
     * 
     * @access private
     */
    private function executeMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $mw) {
            switch ($mw) {
                case 'auth':
                    AuthMiddleware::requireAuth();   // Vérifie authentication basique
                    break;
                case 'admin':
                    AuthMiddleware::requireAdmin();  // Vérifie les droits admin
                    break;
                case 'user':
                    AuthMiddleware::requireUser();   // Vérifie les droits user
                    break;
                    // Possibilité d'étendre avec d'autres middlewares
            }
        }
    }

    /**
     * Appel dynamique d'un contrôleur avec injection de dépendances
     * 
     * Cette méthode gère :
     * - La résolution du namespace du contrôleur
     * - L'instanciation via le container (autowiring)
     * - L'appel de la méthode avec les paramètres de route
     * 
     * @param string $controllerName Nom du contrôleur (avec ou sans namespace)
     * @param string $method Méthode à appeler
     * @param array $params Paramètres de route à passer
     * 
     * @return void
     * 
     * @throws \RuntimeException Si la méthode n'existe pas dans le contrôleur
     * 
     * @access private
     */
    private function call(string $controllerName, string $method, array $params): void
    {
        // Construction du FQCN (Fully Qualified Class Name)
        $controllerClass = str_starts_with($controllerName, 'App\\')
            ? $controllerName
            : "App\\Controller\\{$controllerName}";

        // Instanciation via container (autowiring magique!)
        $controller = $this->container->get($controllerClass);

        // Vérification que la méthode existe
        if (!method_exists($controller, $method)) {
            throw new \RuntimeException(
                "Méthode {$method} introuvable dans {$controllerClass}"
            );
        }

        // Execution du contrôleur avec ses paramètres
        call_user_func_array([$controller, $method], $params);
    }
}
