<?php
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Commentaire.php';
require_once __DIR__ . '/../config/validator.php';

class PostController
{
    private $postModel;
    private $commentModel;
    private $validator;

    public function __construct($connexion)
    {
        if (!$connexion instanceof Connexion) {
            throw new Exception("Erreur : l'objet connexion doit être une instance de la classe Connexion.");
        }

        $this->postModel = new Post($connexion);
        $this->commentModel = new Commentaire($connexion);
        $this->validator = new Validator();
    }
    /**
     * Fonction pour verifier si celui qui est connecter est admin ou pas
     */
    public function isAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?route=admin/login');
            exit;
        }
    }


    /**
     * 🔹 Méthode principale pour gérer les articles (admin/manage_posts)
     */
    public function managePosts()
    {
        $this->isAdmin();

        $articles = $this->postModel->getAllArticles();
        $totalArticles = count($articles);

        require_once __DIR__ . '/../views/admin/manage_posts.php';
    }




    public function getArticleById($id)
    {
        return $this->postModel->voirArticle($id);
    }

    public function create()
    {
        $this->isAdmin();

        $titre = htmlspecialchars(strip_tags(trim($_POST['titre'] ?? '')));
        $contenu = htmlspecialchars(strip_tags(trim($_POST['contenu'] ?? '')));
        $auteurId = $_SESSION['user']['id'];
        $media = $_FILES['media'] ?? null;

        $errors = $this->validator->validateArticleData($titre, $contenu, $auteurId, $media);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?route=admin/manage_posts');
            exit;
        }

        $mediaPath = null;
        $mediaType = null;
        if ($media && $media['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleMediaUpload($media);
            if (!$uploadResult['success']) {
                $_SESSION['errors'][] = $uploadResult['error'];
                header('Location: index.php?route=admin/manage_posts');
                exit;
            }
            $mediaPath = $uploadResult['path'];
            $mediaType = $uploadResult['type'];
        }

        $result = $this->postModel->ajouterArticle($titre, $contenu, $auteurId, $mediaPath, $mediaType);

        $_SESSION['success'] = $result ? "Article publié avec succès." : "Erreur lors de la publication.";
        header('Location: index.php?route=admin/manage_posts');
        exit;
    }

    public function update($id)
    {
        $this->isAdmin();

        $titre = htmlspecialchars(strip_tags(trim($_POST['titre'] ?? '')));
        $contenu = htmlspecialchars(strip_tags(trim($_POST['contenu'] ?? '')));
        $auteurId = $_SESSION['user']['id'] ?? null;

        $errors = [];

        if ($this->validator->isEmpty($titre) || !$this->validator->hasMinLength($titre, 3)) {
            $errors[] = "Le titre doit comporter au moins 3 caractères.";
        }

        if ($this->validator->isEmpty($contenu) || !$this->validator->hasMinLength($contenu, 10)) {
            $errors[] = "Le contenu doit comporter au moins 10 caractères.";
        }

        $mediaPath = null;
        $mediaType = null;

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleMediaUpload($_FILES['media']);
            if (!$uploadResult['success']) {
                $errors[] = $uploadResult['error'];
            } else {
                $mediaPath = $uploadResult['path'];
                $mediaType = $uploadResult['type'];
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?route=admin/manage_posts");
            exit;
        }

        $result = $this->postModel->modifierArticle($id, $titre, $contenu, $auteurId, $mediaPath, $mediaType);

        $_SESSION['success'] = $result ? "Article mis à jour avec succès." : "Erreur lors de la mise à jour.";
        header("Location: index.php?route=admin/manage_posts");
        exit;
    }

    public function delete($id)
    {
        $this->isAdmin();

        $result = $this->postModel->supprimerArticle($id);
        $_SESSION['success'] = $result ? "Article supprimé." : "Erreur lors de la suppression.";
        header("Location: index.php?route=admin/manage_posts");
        exit;
    }

    // public function show($id)
    // {
    //     $article = $this->postModel->voirArticle($id);
    //     $commentaires = $this->commentModel->getCommentairesByArticle($id);
    //     require_once __DIR__ . '/../views/article.php';
    // }

    public function getArticlesForPage(int $page = 1, int $limit = 10): array
    {
        $allArticles = $this->postModel->getAllArticles();
        $offset = ($page - 1) * $limit;
        return array_slice($allArticles, $offset, $limit);
    }

    public function getTotalPages(int $limit = 10): int
    {
        $totalArticles = $this->postModel->countAllArticles();
        return (int) ceil($totalArticles / $limit);
    }

    public function getTotalArticles()
    {
        return $this->postModel->countAllArticles();
    }

    public function search($motCle)
    {
        $articles = $this->postModel->rechercherArticle($motCle);
        require_once __DIR__ . '/../views/search_results.php';
    }

    public function byAuthor($auteurId)
    {
        $articles = $this->postModel->rechercherArticleParAuteur($auteurId);
        require_once __DIR__ . '/../views/articles_by_author.php';
    }

    public function count()
    {
        $total = $this->postModel->countAllArticles();
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function countByAuthor($auteurId)
    {
        $total = $this->postModel->countAllArticlesParAuteur($auteurId);
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    private function handleMediaUpload($media)
    {
        $uniqueName = $this->validator->generateUniqueFileName(
            $this->validator->sanitizeFileName($media['name'])
        );

        $uploadDir = __DIR__ . '/../assets/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . $uniqueName;

        if (move_uploaded_file($media['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'path' => 'assets/uploads/' . $uniqueName,
                'type' => mime_content_type($uploadPath),
                'error' => ''
            ];
        }

        return ['success' => false, 'path' => '', 'type' => '', 'error' => 'Erreur lors du téléchargement du fichier.'];
    }
}
