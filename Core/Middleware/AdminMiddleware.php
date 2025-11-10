<?php

/**
 * Classe AdminMiddleware
 * 
 * Middleware pour restreindre l’accès aux routes administratives
 */
namespace Core\Middleware;

use Core\Session\UserContext;
use Core\Http\Redirector;
use Core\Session\FlashManager;

class AdminMiddleware
{
    public static function handle(): void
    {
        if (!UserContext::isAdmin()) {
            FlashManager::set('error', 'Accès refusé. Vous n\'avez pas les permissions nécessaires.');
            Redirector::to('/unauthorized');
        }
    }
}
