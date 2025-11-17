<?php
/**
 * ---------------------------------------------------------
 *  index.php
 * ---------------------------------------------------------
 * Point d'entrée principal de l'application.
 *
 * Rôle :
 * - Charger automatiquement toutes les classes (Autoloading)
 * - Démarrer la session utilisateur
 * - Initialiser le système de routing
 * - Analyser l'URL et exécuter le contrôleur associé
 *
 * Ce fichier est exécuté à chaque requête HTTP.
 */



spl_autoload_register(function ($class) {

    // Convertit le namespace en chemin de fichier
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Constructeur du chemin physique du fichier
    $file = __DIR__ . '/' . $classPath . '.php';

    // Si le fichier existe, on le charge
    if (file_exists($file)) {
        require_once $file;
    }
});


/* ---------------------------------------------------------
 * 1. DÉMARRAGE DE LA SESSION
 * ---------------------------------------------------------
 * La session permet de conserver des données
 * entre plusieurs requêtes HTTP.
 *
 * Utile pour gérer les utilisateurs connectés,
 * les messages flash, etc.
 */
session_start();


/* ---------------------------------------------------------
 * 2. INITIALISATION DU ROUTER
 * ---------------------------------------------------------
 * Le Router est responsable d'associer une URL
 * à un contrôleur + une méthode.
 *
 * On récupère uniquement le chemin de l'URL :
 *   /articles?id=3 → /articles
 */
use Src\Core\Routing\Router;

// Récupération du chemin de la requête
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Instanciation du router avec le fichier des routes
$router = new Router(__DIR__ . 'Src/Core/Routing/Config/routes.php');


/* ---------------------------------------------------------
 * 3. DISPATCH
 * ---------------------------------------------------------
 * Analyse l’URL et exécute :
 * - le contrôleur défini
 * - la méthode correspondante
 * - puis charge la vue associée
 *
 * Exemple :
 *   URL : /login
 *   → AuthController::login()
 */
$router->dispatch($uri);
