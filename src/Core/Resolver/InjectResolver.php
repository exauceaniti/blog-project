<?php

namespace Core\Resolver;

use Core\Render\Fragment;
use Core\Session\FlashManager;

/**
 * InjectResolver
 * Centralise l’injection des fragments HTML dans une seule methode
 * @injectAll injecte tous les fragments nécessaires (meta, header, footer)
 * celui ci inject aussi les FlashManager pour les messages flash
 * cette methode est ensuite appeles dans le LayoutController::render 
 * et c'est ce qui est directement appeler dans les templates de layout
 * pour afficher automatiquement les fragments requis necessaires pour les pages.
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

    public static function injectFooter(): void
    {
        Fragment::footer();
    }

    public static function injectAll(array $layoutData): void
    {
        self::injectMeta($layoutData);
        self::injectHeader($layoutData);
        FlashManager::inject();
        self::injectFooter();
    }
}
