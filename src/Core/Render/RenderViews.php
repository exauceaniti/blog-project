<?php

namespace Src\Core\Render;

use Src\Core\Lang\MessageBag;
use Src\Core\Resolver\PageTitleResolver;

/**
 * Classe RenderViews
 * ------------------
 * Moteur central de rendu des vues et des layouts.
 * 
 * Cette classe est le cœur du système de rendu de l'application.
 * Elle gère le chargement des vues, l'injection dans les layouts,
 * et la résolution automatique des titres de page.
 *
 * Responsabilités :
 * - Charger une vue PHP et injecter ses variables.
 * - Capturer le contenu de la vue et l'injecter dans un layout.
 * - Résoudre automatiquement le titre de la page via PageTitleResolver + MessageBag.
 * - Gérer les erreurs si une vue ou un layout est introuvable.
 * 
 * @package Src\Core\Render
 */
class RenderViews
{
    /**
     * Rend une vue avec ou sans layout.
     *
     * Le processus de rendu :
     * 1. Résolution du titre de la page (MessageBag → PageTitleResolver → Valeur par défaut)
     * 2. Vérification de l'existence de la vue
     * 3. Si layout spécifié : capture de la vue + injection dans le layout
     * 4. Si pas de layout : affichage direct de la vue
     *
     * @param string      $viewPath  Nom de la vue (ex: "home/index", "admin/users")
     * @param array       $params    Données à injecter dans la vue
     * @param string|null $template  Layout à utiliser (ex: "layout/public", "layout/admin")
     *
     * @throws \Exception Si la vue ou le layout est introuvable
     * 
     * @example
     * // Vue simple sans layout
     * $renderer->renderView('errors/404');
     * 
     * // Vue avec layout et données
     * $renderer->renderView('blog/show', [
     *     'article' => $article,
     *     'comments' => $comments
     * ], 'layout/public');
     * 
     * // Vue admin avec layout admin
     * $renderer->renderView('admin/dashboard', [
     *     'stats' => $stats
     * ], 'layout/admin');
     */
    public function renderView(string $viewPath, array $params = [], ?string $template = null): void
    {
        // Extraction des paramètres pour les rendre accessibles dans la vue
        extract($params);

        /**
         * Résolution du titre de la page selon la priorité :
         * 1. MessageBag (traductions) : "titles.home.index"
         * 2. PageTitleResolver (basé sur l'URL) : "/blog/article-1"
         * 3. Valeur par défaut : "Mon Blog"
         */
        $page_title = MessageBag::get("titles.$viewPath")
            ?? PageTitleResolver::resolve($_SERVER['REQUEST_URI'])
            ?? 'Mon Blog';

        // Construction du chemin complet vers la vue
        $viewFullPath = dirname(__DIR__, 3) . "/Views/{$viewPath}.php";

        // Vérification de l'existence de la vue
        if (!file_exists($viewFullPath)) {
            throw new \Exception("Vue introuvable: {$viewPath}");
        }

        // Rendu avec layout
        if ($template !== null) {
            $templateFullPath = dirname(__DIR__, 3) . "/templates/{$template}.php";

            if (!file_exists($templateFullPath)) {
                throw new \Exception("Layout introuvable: {$template}");
            }

            // Capture du contenu de la vue
            ob_start();
            require $viewFullPath;
            $page_view = ob_get_clean();

            // Variables disponibles dans le layout
            extract([
                'page_view' => $page_view,  // Contenu HTML de la vue
                'title'     => $page_title       // Titre résolu de la page
            ]);

            // Inclusion du layout avec la vue injectée
            require $templateFullPath;
        } else {
            // Rendu direct sans layout
            require $viewFullPath;
        }
    }
}
