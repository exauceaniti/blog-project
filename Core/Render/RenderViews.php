<?php

namespace Core\Render;

/**
 * Classe responsable du rendu des vues avec ou sans template global.
 * Permet d'injecter dynamiquement des variables dans une vue 
 * et de l'encapsuler dans un layout.
 */

class RenderViews
{
    /**
     * Rend une vue PHP avec des paramètres, et l'encapsule dans un template si fourni.
     *
     * @param string $viewPath     Nom du fichier vue (sans extension .php), ex: 'public/home'
     * @param array $params        Tableau associatif de variables à injecter dans la vue
     * @param string|null $template Nom du fichier template (sans extension .php), ex: 'layouts/public_layout'
     *
     * @return void
     */
   public static function renderView(string $viewPath, array $params = [], ?string $template = null): void
{
    extract($params);

    $viewFullPath = dirname(__DIR__, 2) . "/views/{$viewPath}.php";

    if ($template !== null) {
        $templateFullPath = dirname(__DIR__, 2) . "/templates/{$template}.php";

        ob_start();
        require $viewFullPath;
        $page_view = ob_get_clean();

        extract(['page_view' => $page_view]);
        require $templateFullPath;
    } else {
        require $viewFullPath;
    }

    exit();
}

}
