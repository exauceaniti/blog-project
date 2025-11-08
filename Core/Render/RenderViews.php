<?php

namespace Core\Render;

/**
 * Classe responsable du rendu des vues avec ou sans template global.
 * Permet d'injecter dynamiquement des variables dans une vue et de l'encapsuler dans un layout.
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
        // Injecte les variables dans le scope local
        extract($params);

        // Chemin absolu vers la vue
        $path = dirname(__DIR__, 2) . "/views/{$viewPath}.php";

        // Si un template est fourni
        if ($template !== null) {
            $pathTemplate = dirname(__DIR__, 2) . "/templates/{$template}.php";

            // Capture le contenu de la vue
            ob_start();
            include $path;
            $content = ob_get_clean();

            // Injecte le contenu dans une variable pour le template
            extract(["page_view" => $content]);

            // Capture le rendu final du template
            ob_start();
            include $pathTemplate;
            $page = ob_get_clean();

            // Affiche la page complète
            exit($page);
        } else {
            // Affiche directement la vue sans template
            require $path;
        }

        exit();
    }
}
