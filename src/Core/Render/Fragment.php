<?php

namespace App\Core\Render;

/**
 * Classe Fragment - Gestionnaire de fragments de templates
 * 
 * Cette classe fournit des méthodes statiques pour inclure des fragments de templates
 * réutilisables dans l'application (meta, header, footer, composants)
 * 
 * @package App\Core\Render
 */
class Fragment
{
    /**
     * Chemin de base vers les templates d'includes
     * @var string
     */
    protected static string $basePath = __DIR__ . '/../../../templates/includes/';

    /**
     * Inclut le fragment meta.php avec les paramètres donnés
     * 
     * @param string $title Titre de la page (défaut: 'Mon Blog')
     * @param array $params Paramètres supplémentaires à extraire dans le template
     * @return void
     * 
     * @example
     * Fragment::meta('Page Contact', ['description' => 'Description SEO']);
     */
    public static function meta(string $page_title = 'Mon Blog', array $params = []): void
    {
        extract($params);
        include self::$basePath . 'meta.php';
    }

    /**
     * Inclut le fragment header.php avec les paramètres donnés
     * 
     * @param array $params Paramètres à extraire dans le template header
     * @return void
     * 
     * @example
     * Fragment::header(['user' => $currentUser, 'page' => 'home']);
     */
    public static function header(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'header.php';
    }

    /**
     * Inclut le fragment footer.php avec les paramètres donnés
     * 
     * @param array $params Paramètres à extraire dans le template footer
     * @return void
     * 
     * @example
     * Fragment::footer(['showNewsletter' => true]);
     */
    public static function footer(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'footer.php';
    }

    /**
     * Inclut le fragment Sidebar.php avec les parametres donnees
     * 
     * @param array $params parametres a extraires dans le tamplates sidebar 
     * @return void
     * 
     * @example
     * Fragment::Sidebar();
     */
    public static function sidebar(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'Sidebar.php';
    }
    /**
     * Inclu le fragment article_card.php avec les parametres donnes
     * 
     * @param array $params parametres a extraires dans le tamplates article_card
     * @return void
     */
    public static function articleCard(array $params = []): void
    {
        extract($params);
        include self::$basePath . 'article_card.php';
    }

    /**
     * Inclut un composant personnalisé avec ses paramètres
     * 
     * @param string $name Nom du composant (sans extension)
     * @param array $params Paramètres à passer au composant
     * @return void
     * @throws \Exception Si le composant n'existe pas
     * 
     */
    public static function component(string $name, array $params = []): void
    {
        extract($params);
        $path = __DIR__ . '/../Components/' . ucfirst($name) . '.php';

        if (file_exists($path)) {
            include $path;
        } else {
            throw new \Exception("Le composant '$name' n'existe pas.");
        }
    }
}
