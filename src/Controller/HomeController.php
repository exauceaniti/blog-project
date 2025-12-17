<?php

namespace App\Controller;

use App\Service\PostService;
use App\Controller\BaseController;
use App\Core\Session\FlashManager;
use App\Core\Lang\MessageBag;
use App\Core\Http\Redirector;

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
        $this->render('home/articles_detail', ['article' => $post], 'layout/public');
    }

    // Export des 5 derniers
    public function exportLatestPdf(): void
    {
        $posts = $this->postService->getLatestPosts(5);
        $this->sendPdfResponse($posts, "top-5-articles.pdf");
    }

    // Export de TOUS les articles
    public function exportAllPdf(): void
    {
        // Appel à ta méthode existante qui récupère tout
        $posts = $this->postService->getAllPosts(); 
        $this->sendPdfResponse($posts, "archive-complete.pdf");
    }

    // Méthode privée pour éviter la répétition (DRY)
    private function sendPdfResponse(array $posts, string $filename): void
    {
        $pdfContent = $this->postService->generatePdfFromPosts($posts);

        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $pdfContent;
        exit;
    }
}
