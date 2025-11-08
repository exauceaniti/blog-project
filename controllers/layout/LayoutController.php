<?php

namespace controllers\layout;

use Core\Resolver\InjectResolver;
use Core\Resolver\LayoutResolver;
use Core\Resolver\PageTitleResolver;

/**
 * Class LayoutController
 *
 * Ce contrôleur est le chef d’orchestre du système de layout.
 * Il centralise toutes les données globales nécessaires à l’affichage,
 * choisit dynamiquement le bon template selon le rôle utilisateur,
 * injecte les fragments HTML via InjectResolver,
 * et rend la vue finale avec le layout approprié.
 *
 * Cette classe s’appuie sur des résolveurs spécialisés pour éviter toute duplication :
 * - LayoutResolver : sélection du layout selon le rôle
 * - PageTitleResolver : définition du titre selon la route
 * - InjectResolver : injection des fragments HTML
 */
class LayoutController
{
    /**
     * Données globales du layout :
     * - user_connected : booléen indiquant si l'utilisateur est connecté
     * - user_role : rôle de l'utilisateur ('admin', 'user', null)
     * - page_title : titre dynamique de la page
     * - theme : thème visuel ('light', 'dark', etc.) 
     */
    protected array $layoutData;

    /**
     * Initialise les données du layout à partir de la session
     * Permet de rendre le layout contextuel dès l’instanciation
     */
    public function __construct()
    {
        $this->layoutData = [
            'user_connected' => $_SESSION['user'] ?? false,
            'user_role' => $_SESSION['user']['role'] ?? null,
            'page_title' => 'Mon Blog',
            'theme' => $_SESSION['theme'] ?? 'light',
        ];
    }

    /**
     * Retourne le nom du layout à utiliser selon le rôle utilisateur
     * Utilise LayoutResolver pour centraliser la logique de sélection
     */
    public function getResolvedLayout(): string
    {
        return LayoutResolver::resolve($this->layoutData['user_role']);
    }

    /**
     * Rend une vue en l’injectant dans le layout approprié
     * - $viewPath : chemin vers la vue à afficher
     * - $params : données métier à injecter dans la vue
     * Le layout est choisi dynamiquement via getResolvedLayout()
     */
    public function render(string $viewPath, array $params = []): void
    {
        $template = $this->getResolvedLayout();
        $render = new \Core\Render\RenderViews();
        $render->renderView($viewPath, $params, $template);
    }

    /**
     * Définit manuellement le thème visuel (light/dark)
     * Peut être utilisé pour personnaliser l’apparence à la volée
     */
    // public function setTheme(string $theme): void
    // {
    //     $this->layoutData['theme'] = $theme;
    // }

    /**
     * Définit automatiquement le titre selon la route actuelle
     * Utilise PageTitleResolver pour éviter toute logique répétée
     */
    public function autoTitle(string $route): void
    {
        $this->layoutData['page_title'] = PageTitleResolver::resolve($route);
    }

    /**
     * Injecte tous les fragments HTML en une seule ligne :
     * - meta (balises <head>)
     * - header (logo + nav)
     * - nav (menu seul)
     * - footer (bas de page)
     * Utilise InjectResolver pour centraliser l’injection
     */
    public function injectAll(): void
    {
        InjectResolver::injectAll($this->layoutData);
    }

    /**
     * Retourne toutes les données du layout
     * Utile pour le debug, les tests ou les injections manuelles
     */
    public function getLayoutData(): array
    {
        return $this->layoutData;
    }
}
