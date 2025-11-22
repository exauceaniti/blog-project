<?php

namespace Src\Controller;

use Src\Core\Render\RenderViews;
use Src\Core\Session\FlashManager;

class BaseController extends RenderViews
{
    /**
     * Rend une vue avec ou sans layout.
     *
     * @param string $view Nom de la vue (ex: 'public/home')
     * @param array $params Données à injecter dans la vue
     * @param string|null $template Layout à utiliser 
     */
    public function render(string $view, array $params = [], ?string $template = null): void
    {
        // INJECTE AUTOMATIQUEMENT LES MESSAGES FLASH DANS TOUTES LES VUES
        $params = $this->injectFlashMessages($params);
        $this->renderView($view, $params, $template);
    }

    /**
     * Injecte les messages flash dans les paramètres de la vue
     */
    private function injectFlashMessages(array $params): array
    {
        return array_merge($params, [
            'flash' => [
                'error' => FlashManager::get('error'),
                'success' => FlashManager::get('success'),
                'warning' => FlashManager::get('warning'),
                'info' => FlashManager::get('info'),
                'hasError' => FlashManager::has('error'),
                'hasSuccess' => FlashManager::has('success'),
                'hasWarning' => FlashManager::has('warning'),
                'hasInfo' => FlashManager::has('info'),
            ]
        ]);
    }

    /**
     * Redirige vers une URL donnée.
     *
     * @param string $url L'URL cible
     */
    public function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }
}
