<?php 

namespace Core\Resolver;

class PageTitleResolver
{
    public static function resolve(string $route): string
    {
        return match ($route) {
            '/' => 'Accueil',
            '/articles' => 'Nos Articles',
            '/login' => 'Connexion',
            '/register' => 'Inscription',
            '/admin/dashboard' => 'Espace Admin',
            default => 'Mon Blog',
        };
    }
}
