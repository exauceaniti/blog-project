<?php

namespace Src\Controller;

use Src\Service\PostService;
use Src\Controller\BaseController;
use Src\Core\Session\FlashManager;
use Src\Core\Lang\MessageBag;
use Src\Core\Http\Redirector;

/**
 * HomeController
 * Gère l'affichage des vues publiques comme la page d'accueil.
 */
class HomeController extends BaseController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    // --- Actions pour les Vues ---

    /**
     * Affiche la page d'accueil (avec les 5 derniers articles)
     */
    public function accueil(): void
    {
        // Utilisation de la nouvelle méthode optimisée
        $latest_posts = $this->postService->getLatestPosts(5);

        // Rendu de la vue 'home/index' (Votre index.php principal)
        $this->render('home/index', ['latest_articles_list' => $latest_posts], 'layout/public');
    }

    /**
     * Liste de tous les articles (pour la page /articles.php)
     */
    public function articles(): void
    {
        // Le DAO fait le travail lourd (jointure Auteur + COUNT commentaires)
        $posts = $this->postService->getAllPosts();

        // Rendu de la vue 'article/list' (Votre articles.php complet)
        $this->render('home/articles', ['articles_list' => $posts], 'layout/public');
    }

    /**
     * Affiche un article
     */
    public function show(int $id): void
    {
        $post = $this->postService->getPostById($id);
        if (!$post) {
            FlashManager::error(MessageBag::get('article.not_found'));
            Redirector::to('/articles'); // Redirection vers la liste
        }
        $this->render('articles/article_detail', ['article' => $post], 'layout/public');
    }
}
