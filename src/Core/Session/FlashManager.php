<?php
namespace Src\Core\Session;

class FlashManager {
    /**
     * Définit un message flash pour un type donné
     */
    public static function set(string $type, string $message): void {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Raccourcis pratiques
     */
    public static function success(string $message): void {
        self::set('success', $message);
    }

    public static function error(string $message): void {
        self::set('error', $message);
    }

    public static function warning(string $message): void {
        self::set('warning', $message);
    }

    public static function info(string $message): void {
        self::set('info', $message);
    }

    /**
     * Vérifie s’il y a un message pour un type donné
     */
    public static function has(string $type): bool {
        return isset($_SESSION['flash'][$type]);
    }

    /**
     * Récupère et supprime le message flash
     */
    public static function get(string $type): ?string {
        if (!self::has($type)) return null;

        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    /**
     * Injecte automatiquement les messages flash dans la vue
     * Utilise des classes CSS personnalisables : flash-success, flash-error, etc.
     */
    public static function inject(): void {
        $types = ['success', 'error', 'warning', 'info'];

        foreach ($types as $type) {
            if (self::has($type)) {
                $message = self::get($type);
                echo "<div class='flash flash-{$type}'>" . htmlspecialchars($message) . "</div>";
            }
        }
    }
}
