<?php
// Démarrage de la session pour vérifier l'utilisateur connecté
session_start();

// Inclusion des classes nécessaires
require_once '../classes/connexion.php';
require_once '../classes/Post.php';
require_once '../classes/User.php';

// Initialisation de la connexion
$connexion = new Connexion();
$conn = $connexion->connecter(); // PDO
$post = new Post($connexion);
$user = new User($connexion);

// Vérification que l'utilisateur est connecté avant toute action
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté\n"]);
    exit;
}

// Détection de l'action via POST ou GET
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {

    // ========================= AJOUTER UN ARTICLE =========================
    case 'ajouter':
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';

        if (empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "Titre ou contenu manquant\n"]);
            exit;
        }

        $result = $post->ajouterArticle($titre, $contenu, $_SESSION['user_id']);

        echo json_encode(["success" => "Article ajouté avec succès\n"]);
        break;

    // ========================= MODIFIER UN ARTICLE =========================
    case 'modifier':
        $id = $_POST['id'] ?? null;
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';

        if (!$id || empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "ID, titre ou contenu manquant\n"]);
            exit;
        }

        $post->modifierArticle($id, $titre, $contenu);
        echo json_encode(["success" => "Article modifié avec succès\n"]);
        break;

    // ========================= SUPPRIMER UN ARTICLE =========================
    case 'supprimer':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo "ID manquant\n";
            exit;
        }

        $post->supprimerArticle($id);
        echo json_encode(["success" => "Article supprimé avec succès\n"]);
        break;

    // ========================= VOIR TOUS LES ARTICLES =========================
    case 'voir':
        $articles = $post->voirArticles();
        echo json_encode(["success" => true, "articles" => $articles]);
        break;

    // ========================= RECHERCHER UN ARTICLE =========================
    case 'rechercher':
        $motCle = $_GET['motCle'] ?? '';
        if (empty($motCle)) {
            echo "Mot-clé manquant\n";
            exit;
        }

        $result = $post->rechercherArticle($motCle);
        echo json_encode(["success" => true, "resultats" => $result]);
        break;

    // ========================= ACTION NON RECONNUE =========================
    default:
        echo json_encode(["error" => "Action non reconnue\n"]);
        break;
}
