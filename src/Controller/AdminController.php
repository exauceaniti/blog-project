<?php
namespace Src\Controller;

class AdminController extends BaseController
{
    /**
     * Tableau de bord admin
     */
    public function dashboard(): void
    {
        $this->render('admin/dashboard', [
            'title' => 'Espace Administration',
        ], 'layout/admin');
    }

    /**
     * Exemple : gestion des utilisateurs
     */
    public function manageUsers(): void
    {
        $this->render('admin/manage_users', [
            'title' => 'Gestion des utilisateurs'
        ], 'layout/admin');
    }

    /**
     * Exemple : gestion des articles
     */
    public function managePosts(): void
    {
        $this->render('admin/manage_posts', [
            'title' => 'Gestion des articles'
        ], 'layout/admin');
    }
}
