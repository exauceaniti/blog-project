<?php

require_once '../config/connexion.php';
require_once '../models/Post.php';
require_once '../models/commentaire.php';
require_once '..config/validator.php';

class PostController
{
    private $postModel;
    private $commentModel;
    private $validator;

    public function __construct($connexion)
    {
        $this->postModel = new Post($connexion);
        $this->commentModel = new Commentaire($connexion);
        $this->validator = new Validator();
    }

    /**
     * Fonction de création d'article avec validation des données
     */
    public function create()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            // Redirection ou message d'erreur
            echo "Vous devez être connecté pour publier un article.";
            return;
        }

        // Récupérer les données du formulaire
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';
        $auteurId = $_SESSION['user_id'];
        $mediaPath = null;
        $mediaType = null;

        // Valider les champs texte
        $errors = [];
        if (!$this->validator->hasMinLength($titre, 5)) {
            $errors[] = "Le titre doit contenir au moins 5 caractères.";
        }
        if (!$this->validator->hasMinLength($contenu, 20)) {
            $errors[] = "Le contenu est trop court.";
        }

        // Vérifier le média si présent
        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['media'];

            if (!$this->validator->isValidFileType($file, ['image/jpeg', 'image/png', 'video/mp4'])) {
                $errors[] = "Type de fichier non autorisé.";
            }

            if (!$this->validator->isValidFileSize($file, 5 * 1024 * 1024)) {
                $errors[] = "Fichier trop volumineux (max 5 Mo).";
            }

            if (empty($errors)) {
                $safeName = $this->validator->generateUniqueFileName($file['name']);
                $destination = '../assets/uploads' . $safeName;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $mediaPath = 'uploads/' . $safeName;
                    $mediaType = $file['type'];
                } else {
                    $errors[] = "Échec du téléchargement du fichier.";
                }
            }
        }

        // Si erreurs, les afficher
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            return;
        }

        // Appeler le modèle pour ajouter l'article
        $result = $this->postModel->ajouterArticle($titre, $contenu, $auteurId, $mediaPath, $mediaType);

        if ($result) {
            echo "Article publié avec succès.";
            // Redirection possible
        } else {
            echo "Échec de la publication.";
        }
    }



}


































/**
 * Je mets ici toute la logique de contrôle à utiliser dans cette classe PostController.
 *
 * 1- create() → Reçoit les données du formulaire, valide les champs, vérifie le média, appelle ajouterArticle du modèle.
 * 2- update($id) → Reçoit les nouvelles données + id, valide, vérifie le média, appelle modifierArticle.
 * 3- delete($id) → Reçoit l’id, appelle supprimerArticle, gère la redirection ou le message de confirmation.
 * 4- show($id) → Reçoit l’id, appelle voirArticle, transmet les données à la vue.
 * 5- index() → Appelle getAllArticles, transmet la liste à la vue principale.
 * 6- search($motCle) → Reçoit le mot-clé, appelle rechercherArticle, transmet les résultats à la vue.
 * 7- byAuthor($auteurId) → Reçoit l’auteurId, appelle rechercherArticleParAuteur, transmet les articles à la vue.
 * 8- count() → Appelle countAllArticles, retourne le nombre total pour statistiques ou dashboard.
 * 9- countByAuthor($auteurId) → Reçoit l’auteurId, appelle countAllArticlesParAuteur, retourne le total pour cet auteur.
 * 10- paginate($page, $limit) → Reçoit les paramètres de pagination, calcule l’offset, appelle getArticlesPagines, transmet les résultats à la vue.
 */























