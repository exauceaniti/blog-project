<?php
// Démarrage de la session pour vérifier l'utilisateur connecté
session_start();

// Inclusion des classes nécessaires
require_once '../config/connexion.php';
require_once '../models/Post.php';
require_once '../models/User.php';

// Initialisation de la connexion
$connexion = new Connexion();
$post = new Post($connexion);
$user = new User($connexion);

// Vérification que l'utilisateur est connecté avant toute action
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit;
}

// Détection de l'action via POST ou GET
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {

    // ========================= AJOUTER UN ARTICLE AVEC MÉDIA =========================
    case 'ajouter':
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';

        // 🔹 Récupération du fichier média
        $fichierMedia = $_FILES['media'] ?? null;

        if (empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "Titre ou contenu manquant"]);
            exit;
        }

        // 🔹 Validation du fichier si uploadé
        if ($fichierMedia && $fichierMedia['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($fichierMedia['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["error" => "Erreur lors de l'upload du fichier"]);
                exit;
            }

            // 🔹 Validation de la taille du fichier (max 10MB)
            if ($fichierMedia['size'] > 10 * 1024 * 1024) {
                echo json_encode(["error" => "Fichier trop volumineux (max 10MB)"]);
                exit;
            }
        }

        try {
            // 🔹 Appel de la méthode avec gestion du média
            $result = $post->ajouterArticle($titre, $contenu, $_SESSION['user_id'], $fichierMedia);

            if ($result) {
                echo json_encode(["success" => "Article ajouté avec succès"]);
            } else {
                echo json_encode(["error" => "Erreur lors de l'ajout de l'article"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    // ========================= MODIFIER UN ARTICLE AVEC MÉDIA =========================
    case 'modifier':
        $id = $_POST['id'] ?? null;
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';

        // 🔹 Récupération du nouveau fichier média
        $fichierMedia = $_FILES['media'] ?? null;

        if (!$id || empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "ID, titre ou contenu manquant"]);
            exit;
        }

        // 🔹 Validation du fichier si uploadé
        if ($fichierMedia && $fichierMedia['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($fichierMedia['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["error" => "Erreur lors de l'upload du fichier"]);
                exit;
            }

            if ($fichierMedia['size'] > 10 * 1024 * 1024) {
                echo json_encode(["error" => "Fichier trop volumineux (max 10MB)"]);
                exit;
            }
        }

        try {
            // 🔹 Appel de la méthode avec gestion du média
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

    // ========================= SUPPRIMER UN ARTICLE =========================
    case 'supprimer':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(["error" => "ID manquant"]);
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

    // ========================= VOIR TOUS LES ARTICLES =========================
    case 'voir':
        try {
            $articles = $post->voirArticles();
            echo json_encode(["success" => true, "articles" => $articles]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    // ========================= RECHERCHER UN ARTICLE =========================
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

    // ========================= ACTION NON RECONNUE =========================
    default:
        echo json_encode(["error" => "Action non reconnue"]);
        break;
}
