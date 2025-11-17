<?php
namespace Src\Core\Render;

use Src\Core\Lang\MessageBag;
use Src\Core\Resolver\PageTitleResolver;

/**
 * Classe RenderViews
 * ------------------
 * Moteur central de rendu des vues et des layouts.
 *
 * Responsabilités :
 * - Charger une vue PHP et injecter ses variables.
 * - Capturer le contenu de la vue et l’injecter dans un layout.
 * - Résoudre automatiquement le titre de la page via PageTitleResolver + MessageBag.
 * - Gérer les erreurs si une vue ou un layout est introuvable.
 */
class RenderViews
{
    /**
     * Rend une vue avec ou sans layout.
     *
     * @param string      $viewPath  Nom de la vue (ex: "home/index")
     * @param array       $params    Données à injecter dans la vue
     * @param string|null $template  Layout à utiliser (ex: "layout/public")
     *
     * @throws \Exception Si la vue ou le layout est introuvable
     */
    public function renderView(string $viewPath, array $params = [], ?string $template = null): void
    {
        // 1 Injection des variables dans le scope local
        extract($params);

        // 2 Résolution automatique du titre
        // Exemple : "home/index" → MessageBag::get("titles.home.index")
        $title = MessageBag::get("titles.$viewPath") 
            ?? PageTitleResolver::resolve($_SERVER['REQUEST_URI']) 
            ?? 'Mon Blog';

        // 3 Construction du chemin absolu vers la vue
        $viewFullPath = dirname(__DIR__, 3) . "/Views/{$viewPath}.php";

        if (!file_exists($viewFullPath)) {
            throw new \Exception("Vue introuvable: {$viewPath}");
        }

        // 4 Si un layout est fourni
        if ($template !== null) {
            $templateFullPath = dirname(__DIR__, 3) . "/templates/{$template}.php";

            if (!file_exists($templateFullPath)) {
                throw new \Exception("Layout introuvable: {$template}");
            }

            // Capture du contenu de la vue
            ob_start();
            require $viewFullPath;
            $page_view = ob_get_clean();

            // Injection des variables globales dans le layout
            extract([
                'page_view' => $page_view,
                'title'     => $title
            ]);

            require $templateFullPath;
        } else {
            // Pas de layout → affichage direct de la vue
            require $viewFullPath;
        }
    }
}
