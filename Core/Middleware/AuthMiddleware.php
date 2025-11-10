<?php

/**
 * Classe AuthMiddleware
 * 
 * Middleware pour restreindre l’accès aux routes protégées
 */
namespace Core\Middleware;

use Core\Session\UserContext;
use Core\Http\Redirector;
use Core\Session\FlashManager;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!UserContext::isAuthenticated()) {
            FlashManager::set('error', 'Veuillez vous connecter pour accéder ici.');
            Redirector::to('/login');
        }
    }
}
