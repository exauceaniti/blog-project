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
 * Rend une vue PHP avec des variables injectées, avec ou sans encapsulation dans un layout.
 *
 * @param string $viewPath   Le chemin relatif vers la vue (ex: 'public/home' pour 'views/public/home.php')
 * @param array $params      Les variables à rendre disponibles dans la vue (ex: ['user' => $user])
 * @param string|null $template Le nom du layout à utiliser (ex: 'public-layout' pour 'templates/public-layout.php')
 *
 * Fonctionnement :
 * - Si un layout est fourni :
 *   1. Charge la vue et capture son contenu dans $page_view
 *   2. Injecte $page_view + les variables dans le layout
 *   3. Affiche le layout complet
 * - Si aucun layout n’est fourni :
 *   1. Affiche directement la vue seule
 */

   public static function renderView(string $viewPath, array $params = [], ?string $template = null): void
{
    /**injecte toutes les variables dans un scope local
     * Donc la variable est sera aeecessible seulement dans la classe local
    */
    extract($params);

    //Construit le chemin absolue vers la vue demander.
    $viewFullPath = dirname(__DIR__, 2) . "/views/{$viewPath}.php";

    //Si un layout est specifier, on encapsule la vue dedans
    if ($template !== null) {
        $templateFullPath = dirname(__DIR__, 2) . "/templates/{$template}.php";

        //Capture le rendu HTML de la vue dans la variable $page_view (sans afficher tout de suite)
        ob_start();
        require $viewFullPath;
        $page_view = ob_get_clean();

        //Injecte $page_view + les variables dans le layout puis affiche le layout complet
        extract(['page_view' => $page_view]);
        require $templateFullPath;
    } else {
        require $viewFullPath;//Affiche directement la vue si un layout est choisie
    }

    exit();//fin propre de l'executions.
}

}
