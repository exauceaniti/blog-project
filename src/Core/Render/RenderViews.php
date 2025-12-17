<?php

namespace App\Core\Render;

use App\Core\Resolver\PageTitleResolver;
use App\Core\Routing\RouteContext;

/**
 * Moteur de rendu de vues - Gestionnaire de templates et layouts
 * 
 * Cette classe gère le rendu des vues avec système de templates, injection
 * de variables et résolution automatique des titres de pages.
 * 
 * @package App\Core\Render
 */
class RenderViews
{
    /**
     * Rend une vue avec son template et variables
     * 
     * Processus de rendu :
     * 1. Récupération du contexte de route
     * 2. Résolution du titre de page
     * 3. Vérification des chemins de fichiers
     * 4. Capture du contenu de la vue
     * 5. Injection dans le template
     * 
     * @param string $viewPath Chemin relatif de la vue (sans extension)
     * @param array $params Variables à passer à la vue
     * @param string|null $template Nom du template à utiliser (null = rendu direct)
     * 
     * @return void
     * 
     * @throws \Exception Si la vue ou le template est introuvable
     * 
     * @example
     * $renderer->renderView('posts/show', ['post' => $post], 'default');
     */

    public function renderView(string $viewPath, array $params = [], ?string $template = null): void
    {
        // NETTOYAGE DES BUFFERS EXISTANTS
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $routeKey = RouteContext::getRouteKey() ?? 'Unknown@unknown';
        $routeParams = RouteContext::getParams();
        $page_title = PageTitleResolver::resolve($routeKey, $routeParams) ?? 'Mon Blog';

        $viewFullPath = dirname(__DIR__, 3) . "/Views/{$viewPath}.php";
        if (!file_exists($viewFullPath)) {
            throw new \Exception("Vue introuvable: {$viewPath}");
        }

        if ($template !== null) {
            $templateFullPath = dirname(__DIR__, 3) . "/templates/{$template}.php";
            if (!file_exists($templateFullPath)) {
                throw new \Exception("Layout introuvable: {$template}");
            }

            // CAPTURE DE LA VUE
            ob_start();
            extract($params);
            require $viewFullPath;
            $page_view = ob_get_clean();

            // RENDU FINAL AVEC NOUVEAU BUFFER
            ob_start();
            extract([
                'page_view'  => $page_view,
                'page_title' => $page_title,
            ]);
            require $templateFullPath;

            // ENVOI DU CONTENU
            ob_end_flush();
        } else {
            // Rendu direct
            extract($params);
            require $viewFullPath;
        }
    }
}
