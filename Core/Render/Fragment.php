<?php

namespace Core\Render;

/**
 * Classe Fragment
 * Gère l'injection des fragments HTML réutilisables dans les layouts :
 * - meta (balises <head>)
 * - header (logo + nav)
 * - nav (menu seul)
 * - footer (bas de page ou pied de page)
 */
class Fragment
{
    /**
     * Chemin de base vers le dossier includes/
     */
    protected static string $basePath = __DIR__ . '/../../includes/';
    /**
     * Injecte le fragment meta.php
     */
    public static function meta(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'meta.php';
    }

    /**
     * Injecte le fragment header.php
     */
    public static function header(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'header.php';
    }

    /**
     * Injecte le fragment nav.php
     */
    public static function nav(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'nav.php';
    }

    /**
     * Injecte le fragment footer.php
     */
    public static function footer(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'footer.php';
    }
}
