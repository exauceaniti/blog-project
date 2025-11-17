<?php
namespace controllers;

use models\Post;
use Core\BaseController;

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

        $this->render('public/home', [
            'articles_list' => $articles
        ], 'layouts/public');
    }

    /**
     * Page de liste complète des articles
     */
    public function articles(): void
    {
        $articles = $this->post->getAllArticles();

        $this->render('public/articles', [
            'articles_list' => $articles
        ], 'layouts/public');
    }

    /**
     * Page détail d’un article
     */
    public function show(int $id): void
    {
        $article = $this->post->getArticleById($id);

        if (!$article) {
            $this->redirect('/articles');
        }

        $this->render('public/article_detail', [
            'article' => $article
        ], 'layouts/public');
    }
}
