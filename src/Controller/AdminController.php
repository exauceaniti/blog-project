<?php

namespace App\Controller;

use App\Service\PostService;
use App\Controller\BaseController;
use App\Core\Session\FlashManager;
use App\Core\Lang\MessageBag;

/**
 * AdminController
 * Gère l'affichage des vues et des interfaces de l'espace d'administration.
 */
class AdminController extends BaseController
{
    private PostService $postService;

    // Injection du PostService
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    // ------------------------------------------------------------------
    // VUES GÉNÉRALES
    // ------------------------------------------------------------------

    /**
     * Tableau de bord admin
     */
    public function dashboard(): void
    {
        $this->render('admin/dashboard', [
            'title' => 'Espace Administration',
        ], 'layout/admin');
    }

    // ------------------------------------------------------------------
    // VUES DE GESTION DES ARTICLES
    // ------------------------------------------------------------------

    /**
     * Affiche la liste complète des articles avec options Modifier/Supprimer.
     * C'est la vue "adminIndex" que nous avions nommée dans le routing.
     */
    public function managePosts(): void
    {
        // 1. Récupérer tous les articles (avec Auteur et Compte Commentaires)
        $posts = $this->postService->getAllPosts();

        // 2. Rendu de la vue de gestion (qui contient le tableau HTML)
        $this->render('admin/post_manager', [
            'title' => 'Gestion des Articles',
            'articles_list' => $posts,
        ], 'layout/admin');
    }

    /**
     * Affiche le formulaire pour créer un nouvel article.
     * (C'est la méthode "displayCreateForm" du routing)
     */
    public function ajouterArticle(): void
    {
        // Le formulaire d'ajout n'a pas besoin de données d'article initiales.
        $this->render('admin/ajouter', [
            'title' => 'Créer un Article',
            'article' => null, // Pas d'objet article pour l'ajout
        ], 'layout/admin');
    }

    /**
     * Affiche le formulaire pour modifier un article existant.
     * (C'est la méthode "displayUpdateForm" du routing)
     *
     * @param int $id ID de l'article à modifier
     */
    public function modifierArticle(int $id): void
    {
        // 1. Récupérer l'article à modifier
        $post = $this->postService->getPostById($id);

        if (!$post) {
            // Gérer le cas où l'article n'existe pas
            FlashManager::error(MessageBag::get('article.not_found'));
            $this->redirect('/admin/posts');
            return;
        }

        // 2. Rendu du formulaire (le fragment form_article sera inclus dans cette vue)
        $this->render('admin/modifer', [
            'title' => 'Modification Article #' . $id,
            'article' => $post, // Passe l'objet Post pour pré-remplir le formulaire
        ], 'layout/admin');
    }
    
    // ------------------------------------------------------------------
    // VUES DE GESTION DES UTILISATEURS
    // ------------------------------------------------------------------

    /**
     * Affiche la page de gestion des utilisateurs
     */
    public function manageUsers(): void
    {
        // Ici, vous auriez besoin d'injecter et d'appeler un UserService->getAllUsers()
        $this->render('admin/manage_users', [
            'title' => 'Gestion des Utilisateurs'
        ], 'layout/admin');
    }
}
