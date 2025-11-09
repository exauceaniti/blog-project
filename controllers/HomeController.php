<?php

namespace controllers;

// Contrôleur métier : gère les pages publiques liées aux articles

use models\Post;
use Core\BaseController;
use controllers\layout\LayoutController;

require_once __DIR__ . '/BaseController.php';
require_once dirname(__DIR__) . '/models/Post.php';

class HomeController extends BaseController
{
    /**
     * @var Post Modèle métier pour la gestion des articles
     */
    private Post $post;

    /**
     * Constructeur
     * Initialise le modèle Post avec la connexion centralisée
     */
    public function __construct()
    {
        $this->post = new Post(\Connexion::getInstance());
    }

    /**
     * Page d’accueil publique
     * Affiche les articles récents
     */
    public function index(): void
    {
        $articles = $this->post->getAllArticles();

        $layout = new LayoutController();
        $layout->autoTitle($_SERVER['REQUEST_URI']);
        $layout->render('public/home', [
            'articles_list' => $articles
        ]);
    }

    /**
     * Page de liste complète des articles
     */
    public function articles(): void
    {
        $articles = $this->post->getAllArticles();

        $layout = new LayoutController();
        $layout->autoTitle($_SERVER['REQUEST_URI']);
        $layout->render('public/articles', [
            'articles_list' => $articles
        ]);
    }
}
