<?php

namespace Src\Core\Middleware;

use Src\Core\Http\Redirector;
use Src\Core\Session\FlashManager;
use Src\Core\Lang\MessageBag;

class AuthMiddleware
{
    /**
     * Vérifie si l'utilisateur est connecté.
     * On verifie si la sessions est demarree et que l'idee est bien present.
     */
    private static function checkAuth(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }


    /**
     * Vérifie le rôle de l'utilisateur (on utilise la clé standardisée 'user_role').
     * La clef de sessions doit etre la meme que ce qui se trouve dans 
     * le Controller.
     */
    private static function hasRole(string $role): bool
    {
        return ($_SESSION['user_role'] ?? '') === $role;
    }

    /**
     * Middleware générique : vérifie l'authentification et éventuellement le rôle.
     */
    public static function handle(?string $requiredRole = null): void
    {
        if (!self::checkAuth()) {
            // 1. Capture l'URL actuelle pour redirection après login
            // On s'assure de ne pas capturer les pages de connexion/inscription elles-mêmes
            if (
                $_SERVER['REQUEST_URI'] !== '/user/login'
                && $_SERVER['REQUEST_URI'] !== '/user/register'
            ) {
                $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            }

            // 2. Message d'erreur et redirection vers la page de login
            FlashManager::error(MessageBag::get('auth.required'));
            Redirector::to('/user/login');
            exit;
        }

        if ($requiredRole && !self::hasRole($requiredRole)) {
            // L'utilisateur est connecté mais n'a pas le bon rôle (ex: n'est pas admin)
            $key = $requiredRole === 'admin' ? 'auth.admin_only' : 'auth.forbidden';
            FlashManager::error(MessageBag::get($key));

            // Redirection vers une page d'accès refusé
            Redirector::to('/unauthorized');
            exit;
        }
    }

    // Alias pratiques (inchangés)
    public static function requireAuth(): void
    {
        self::handle();
    }

    public static function requireAdmin(): void
    {
        self::handle('admin');
    }
}
