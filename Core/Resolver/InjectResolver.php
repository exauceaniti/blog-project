<?php

namespace Core\Resolver;

use Core\Render\Fragment;

/**
 * InjectResolver
 * Centralise l’injection des fragments HTML dans le layout
 */
class InjectResolver
{
    public static function injectMeta(array $layoutData): void
    {
        Fragment::meta([
            'page_title' => $layoutData['page_title'] ?? 'Mon Blog',
            'theme' => $layoutData['theme'] ?? 'light',
        ]);
    }

    public static function injectHeader(array $layoutData): void
    {
        Fragment::header($layoutData);
    }

    public static function injectNav(array $layoutData): void
    {
        Fragment::nav($layoutData);
    }

    public static function injectFooter(): void
    {
        Fragment::footer();
    }

    public static function injectAll(array $layoutData): void
    {
        self::injectMeta($layoutData);
        self::injectHeader($layoutData);
        self::injectNav($layoutData);
        self::injectFooter();
    }
}
