<?php

namespace Core\Session;

use Core\Render\Fragment;

class FlashManager
{
    /**
     * Définit un message flash
     */
    public static function set(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Vérifie s’il y a un message flash
     */
    public static function has(): bool
    {
        return isset($_SESSION['flash']);
    }

    /**
     * Récupère et supprime le message flash
     */
    public static function get(): ?array
    {
        if (!self::has()) return null;

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Injecte automatiquement le composant Alert si un message existe
     */
    public static function inject(): void
    {
        $flash = self::get();
        if ($flash) {
            Fragment::component('alert', [
                'type' => $flash['type'],
                'message' => $flash['message']
            ]);
        }
    }
}
