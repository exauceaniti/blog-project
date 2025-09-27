<?php
session_start();

require_once '../config/connexion.php';
require_once '../models/Post.php';
require_once '../models/User.php';

$connexion = new Connexion();
$post = new Post($connexion);
$user = new User($connexion);

// Vérification authentification
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {

    case 'ajouter':
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $fichierMedia = $_FILES['media'] ?? null;

        if (empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "Titre ou contenu manquant"]);
            exit;
        }

        // Validation du fichier si présent
        if ($fichierMedia && $fichierMedia['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($fichierMedia['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["error" => "Erreur lors de l'upload du fichier"]);
                exit;
            }

            // Taille max dynamique
            $maxSize = 10 * 1024 * 1024; // 10MB par défaut
            if (strpos($fichierMedia['type'], 'video') !== false) {
                $maxSize = 50 * 1024 * 1024;
            } elseif (strpos($fichierMedia['type'], 'audio') !== false) {
                $maxSize = 15 * 1024 * 1024;
            }

            if ($fichierMedia['size'] > $maxSize) {
                echo json_encode(["error" => "Fichier trop volumineux"]);
                exit;
            }
        }

        try {
            $result = $post->ajouterArticle($titre, $contenu, $_SESSION['user_id'], $fichierMedia);

            if ($result) {
                // Si le formulaire a un flag "from_form", on redirige
                if (!empty($_POST['from_form'])) {
                    header("Location: ../index.php?success=1");
                    exit;
                }
                echo json_encode(["success" => "Article ajouté avec succès"]);
            } else {
                echo json_encode(["error" => "Erreur lors de l'ajout de l'article"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    case 'modifier':
        $id = $_POST['id'] ?? null;
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $fichierMedia = $_FILES['media'] ?? null;

        if (!$id || empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "ID, titre ou contenu manquant"]);
            exit;
        }

        // Vérifier que l'article existe et appartient à l'utilisateur
        $article = $post->getArticleById($id);
        if (!$article || $article['auteur_id'] != $_SESSION['user_id']) {
            echo json_encode(["error" => "Vous n'avez pas les droits pour modifier cet article"]);
            exit;
        }

        if ($fichierMedia && $fichierMedia['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($fichierMedia['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["error" => "Erreur lors de l'upload du fichier"]);
                exit;
            }

            $maxSize = 10 * 1024 * 1024;
            if (strpos($fichierMedia['type'], 'video') !== false) {
                $maxSize = 50 * 1024 * 1024;
            } elseif (strpos($fichierMedia['type'], 'audio') !== false) {
                $maxSize = 15 * 1024 * 1024;
            }

            if ($fichierMedia['size'] > $maxSize) {
                echo json_encode(["error" => "Fichier trop volumineux"]);
                exit;
            }
        }

        try {
            $result = $post->modifierArticle($id, $titre, $contenu, $fichierMedia);

            if ($result) {
                echo json_encode(["success" => "Article modifié avec succès"]);
            } else {
                echo json_encode(["error" => "Erreur lors de la modification de l'article"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    case 'supprimer':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(["error" => "ID manquant"]);
            exit;
        }

        // Vérifier droits
        $article = $post->getArticleById($id);
        if (!$article || $article['auteur_id'] != $_SESSION['user_id']) {
            echo json_encode(["error" => "Vous n'avez pas les droits pour supprimer cet article"]);
            exit;
        }

        try {
            $result = $post->supprimerArticle($id);

            if ($result) {
                echo json_encode(["success" => "Article supprimé avec succès"]);
            } else {
                echo json_encode(["error" => "Erreur lors de la suppression de l'article"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    case 'voir':
        try {
            $articles = $post->voirArticles();
            echo json_encode(["success" => true, "articles" => $articles]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    case 'rechercher':
        $motCle = $_GET['motCle'] ?? '';

        if (empty($motCle)) {
            echo json_encode(["error" => "Mot-clé manquant"]);
            exit;
        }

        try {
            $resultats = $post->rechercherArticle($motCle);
            echo json_encode(["success" => true, "resultats" => $resultats]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(["error" => "Action non reconnue"]);
        break;
}
