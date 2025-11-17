<?php

namespace Src\Controller;

use Src\Core\Render\RenderViews;

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
        $this->renderView($view, $params, $template);
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
