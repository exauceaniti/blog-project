<?php

namespace App\Controller;

use App\Core\Resolver\LayoutResolver;
use App\Core\Resolver\PageTitleResolver;
use App\Core\Session\UserContext;

/**
 * Class LayoutController
 *
 * Chef d’orchestre du layout :
 * - Sélection du bon template selon le rôle
 * - Injection des fragments HTML
 * - Définition du titre dynamique
 * - Affichage des messages flash
 * - Passage des infos utilisateur aux vues
 */
class LayoutController
{
    protected array $layoutData;

    public function __construct()
    {
        $this->layoutData = [
            'user_connected' => UserContext::isAuthenticated(),
            'user_role' => UserContext::getUser()['role'] ?? null,
            'username' => UserContext::getName(),
            'email' => UserContext::getEmail(),
            // 'page_title' => 'Mon Blog',
            // 'theme' => $_SESSION['theme'] ?? 'light',
        ];
    }

    /**
     * Retourne le layout à utiliser selon le rôle
     */
    public function getResolvedLayout(): string
    {
        return LayoutResolver::resolve($this->layoutData['user_role']);
    }

    /**
     * Rend la vue avec le layout et les fragments injectés
     * Injecte les fragments avant le rendu
     */
    public function render(string $viewPath, array $params = []): void
    {
        $template = $this->getResolvedLayout();
        $render = new \Core\Render\RenderViews();

        // Fusionne les données de layout avec les données de vue
        $mergedParams = array_merge($params, $this->layoutData);

        // Rend la vue avec le layout
        $render->renderView($viewPath, $mergedParams, $template);
    }


    /**
     * Définit automatiquement le titre selon la route
     */
    public function autoTitle(string $route): void
    {
        $this->layoutData['page_title'] = PageTitleResolver::resolve($route);
    }

    /**
     * Retourne toutes les données du layout
     */
    public function getLayoutData(): array
    {
        // var_dump($this->layoutData);
        return $this->layoutData;
    }
}
