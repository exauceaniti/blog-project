<?php
namespace Src\Core\Middleware;

use Src\Core\Http\Redirector;
use Src\Core\Session\FlashManager;
use Src\Core\Lang\MessageBag;

class AuthMiddleware {
    /**
     * Vérifie si l'utilisateur est connecté
     * Si non, capture l'URL actuelle et redirige vers /login
     */
    public static function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

            FlashManager::error(MessageBag::get('auth.required'));
            Redirector::to('/login');
            exit;
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté ET est admin
     * Sinon redirige vers /unauthorized
     */
    public static function requireAdmin(): void {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

            FlashManager::error(MessageBag::get('auth.required'));
            Redirector::to('/login');
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            FlashManager::error(MessageBag::get('auth.admin_only'));
            Redirector::to('/unauthorized');
            exit;
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté ET est un utilisateur standard
     * Sinon redirige vers /unauthorized
     */
    public static function requireUser(): void {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

            FlashManager::error(MessageBag::get('auth.required'));
            Redirector::to('/login');
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'user') {
            FlashManager::error(MessageBag::get('auth.user_only'));
            Redirector::to('/unauthorized');
            exit;
        }
    }
}
