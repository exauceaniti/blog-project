<?php
namespace Src\Core\Middleware;

use Src\Core\Http\Redirector;
use Src\Core\Session\FlashManager;
use Src\Core\Lang\MessageBag;

class AuthMiddleware
{
    /**
     * Vérifie si l'utilisateur est connecté
     */
    private static function checkAuth(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifie le rôle de l'utilisateur
     */
    private static function hasRole(string $role): bool
    {
        return ($_SESSION['role'] ?? '') === $role;
    }

    /**
     * Middleware générique : vérifie l'authentification et éventuellement le rôle
     */
    public static function handle(?string $requiredRole = null): void
    {
        if (!self::checkAuth()) {
            // Capture l'URL actuelle pour redirection après login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

            FlashManager::error(MessageBag::get('auth.required'));
            Redirector::to('/user/login');
            exit;
        }

        if ($requiredRole && !self::hasRole($requiredRole)) {
            $key = $requiredRole === 'admin' ? 'auth.admin_only' : 'auth.user_only';
            FlashManager::error(MessageBag::get($key));
            //Mise à jour du chemin unauthorized
            Redirector::to('/unauthorized');
            exit;
        }
    }

    /**
     * Alias pratiques
     */
    public static function requireAuth(): void
    {
        self::handle();
    }

    public static function requireAdmin(): void
    {
        self::handle('admin');
    }

    public static function requireUser(): void
    {
        self::handle('user');
    }
}
