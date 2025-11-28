<?php

namespace Src\Core\Middleware;

// Importation essentielle du service de session
use Src\Core\Session\SessionService;
use Src\Core\Http\Redirector;
use Src\Core\Session\FlashManager;
use Src\Core\Lang\MessageBag;

/**
 * Middleware d'authentification et d'autorisation
 * * Gère la vérification des sessions utilisateur et les contrôles d'accès
 */
class AuthMiddleware
{
    // On retire checkAuth() et hasRole() car SessionService le fait

    /**
     * Middleware générique : vérifie l'authentification et éventuellement le rôle
     * * @param string|null $requiredRole Rôle requis (null pour simple authentification)
     * @return void Redirige vers la page de login ou unauthorized si échec
     */
    public static function handle(?string $requiredRole = null): void
    {
        // On s'assure que la session est démarrée avant toute vérification ou écriture
        SessionService::startIfNotStarted();

        // 1. VÉRIFICATION DE L'AUTHENTIFICATION (Utilisation de SessionService)
        if (!SessionService::isLoggedIn()) {

            // Capture l'URL actuelle pour redirection après login
            if (
                $_SERVER['REQUEST_URI'] !== '/login'
                && $_SERVER['REQUEST_URI'] !== '/register'
            ) {
                $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            }

            // Message d'erreur et redirection vers la page de login
            FlashManager::error(MessageBag::get('auth.required'));
            Redirector::to('/login'); // Utilisez le bon chemin ici (probablement /login)
            exit;
        }

        // 2. VÉRIFICATION DU RÔLE (Utilisation de SessionService)
        if ($requiredRole && SessionService::getRole() !== $requiredRole) {

            // L'utilisateur est connecté mais n'a pas le bon rôle (ex: n'est pas admin)
            $key = $requiredRole === 'admin' ? 'auth.admin_only' : 'auth.forbidden';
            FlashManager::error(MessageBag::get($key));

            // Redirection vers une page d'accès refusé
            Redirector::to('/unauthorized');
            exit;
        }
    }

    /**
     * Alias pour exiger une authentification simple
     * * @return void
     */
    public static function requireAuth(): void
    {
        self::handle();
    }

    /**
     * Alias pour exiger le rôle administrateur
     * * @return void
     */
    public static function requireAdmin(): void
    {
        self::handle('admin');
    }
}
