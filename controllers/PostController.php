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
        // ✅ On attend ici un objet Connexion, pas PDO
        if (!$connexion instanceof Connexion) {
            throw new Exception("Erreur : l'objet connexion doit être une instance de la classe Connexion.");
        }

        $this->postModel = new Post($connexion);
        $this->commentModel = new Commentaire($connexion);
        $this->validator = new Validator();
    }


    /**
     * 🔹 Créer un article avec validation et upload média
     */
    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'][] = "Vous devez être connecté pour publier un article.";
            header('Location: /index.php');

            exit;
        }

        $titre = htmlspecialchars(strip_tags(trim($_POST['titre'] ?? '')));
        $contenu = htmlspecialchars(strip_tags(trim($_POST['contenu'] ?? '')));
        $auteurId = $_SESSION['user_id'];
        $media = $_FILES['media'] ?? null;

        $errors = $this->validator->validateArticleData($titre, $contenu, $auteurId, $media);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /index.php');

            exit;
        }

        // Upload média
        $mediaPath = null;
        $mediaType = null;
        if ($media && $media['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleMediaUpload($media);
            if (!$uploadResult['success']) {
                $_SESSION['errors'][] = $uploadResult['error'];
                header('Location: /index.php');

                exit;
            }
            $mediaPath = $uploadResult['path'];
            $mediaType = $uploadResult['type'];
        }

        $result = $this->postModel->ajouterArticle($titre, $contenu, $auteurId, $mediaPath, $mediaType);
        $_SESSION['success'] = $result ? "Article publié avec succès." : "Erreur lors de la publication.";
        header('Location: /index.php');

        exit;
    }

    /**
     * 🔹 Mettre à jour un article existant
     */
    public function update($id)
    {
        $titre = htmlspecialchars(strip_tags(trim($_POST['titre'] ?? '')));
        $contenu = htmlspecialchars(strip_tags(trim($_POST['contenu'] ?? '')));
        $auteurId = $_SESSION['user_id'] ?? null;

        $errors = [];
        if ($this->validator->isEmpty($titre) || !$this->validator->hasMinLength($titre, 3)) {
            $errors[] = "Le titre est invalide.";
        }
        if ($this->validator->isEmpty($contenu) || !$this->validator->hasMinLength($contenu, 10)) {
            $errors[] = "Le contenu est invalide.";
        }

        // Upload média
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
            header("Location: index.php?id=$id");
            exit;
        }

        $result = $this->postModel->modifierArticle($id, $titre, $contenu, $auteurId, $mediaPath, $mediaType);
        $_SESSION['success'] = $result ? "Article mis à jour." : "Erreur lors de la mise à jour.";
        header("Location: index.php?id=$id");
        exit;
    }

    /**
     * 🔹 Supprimer un article
     */
    public function delete($id)
    {
        $result = $this->postModel->supprimerArticle($id);
        $_SESSION['success'] = $result ? "Article supprimé." : "Erreur lors de la suppression.";
        header("Location: index.php");
        exit;
    }

    /**
     * 🔹 Afficher un article et ses commentaires
     */
    public function show($id)
    {
        $article = $this->postModel->voirArticle($id);
        require_once __DIR__ . '/../views/article.php';
    }

    /**
     * 🔹 Liste tous les articles avec pagination
     * @param int $limit Nombre d'articles par page
     * @param int $offset Décalage pour la pagination
     * @return void
     */
    public function getArticlesForPage($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        return $this->postModel->getArticlesPagines($limit, $offset);
    }

    public function getTotalArticles()
    {
        return $this->postModel->countAllArticles();
    }



    /**
     * 🔹 Recherche par mot-clé
     */
    public function search($motCle)
    {
        $articles = $this->postModel->rechercherArticle($motCle);
        require_once __DIR__ . '/../index.php';

    }

    /**
     * 🔹 Articles par auteur
     */
    public function byAuthor($auteurId)
    {
        $articles = $this->postModel->rechercherArticleParAuteur($auteurId);
        require_once __DIR__ . '/../index.php';

    }

    /**
     * 🔹 Statistiques : total d'articles
     */
    public function count()
    {
        $total = $this->postModel->countAllArticles();
        require_once __DIR__ . '/../admin/dashboard.php';
    }

    /**
     * 🔹 Statistiques : total par auteur
     */
    public function countByAuthor($auteurId)
    {
        $total = $this->postModel->countAllArticlesParAuteur($auteurId);
        require_once __DIR__ . '/../admin/dashboard.php';
    }

    /**
     * 🔹 Gérer l’upload d’un média
     * @param array $media $_FILES['media']
     * @return array ['success'=>bool, 'path'=>string, 'type'=>string, 'error'=>string]
     */
    private function handleMediaUpload($media)
    {
        $safeName = $this->validator->sanitizeFileName($media['name']);
        $uniqueName = $this->validator->generateUniqueFileName($safeName);
        $uploadDir = __DIR__ . '/../assets/uploads/';

        if (!file_exists($uploadDir))
            mkdir($uploadDir, 0777, true);

        $uploadPath = $uploadDir . $uniqueName;
        if (move_uploaded_file($media['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'path' => '/assets/uploads/' . $uniqueName,
                'type' => mime_content_type($uploadPath),
                'error' => ''
            ];
        }

        return ['success' => false, 'path' => '', 'type' => '', 'error' => 'Erreur lors du téléchargement du fichier.'];
    }
}
