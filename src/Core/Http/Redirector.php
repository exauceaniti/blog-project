<?php

namespace App\Core\Http;

class Redirector
{
    /**
     * Redirige vers une URL absolue
     */
    public static function to(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Redirige vers la page précédente
     * Je stoque l'URL précédente dans la variable HTTP_REFERER
     */
    public static function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        self::to($referer);
    }
}
