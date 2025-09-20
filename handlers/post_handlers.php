<?php
// Démarrage de la session pour vérifier l'utilisateur connecté
session_start();

// Inclusion des classes nécessaires
require_once '../config/database.php';
require_once '../classes/Post.php';
require_once '../classes/User.php';

// Initialisation de la connexion
$connexion = new Connexion();
$conn = $connexion->connecter(); // PDO
$post = new Post($connexion);
$user = new User($connexion);

// Vérification que l'utilisateur est connecté avant toute action
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour effectuer cette action\n";
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
            echo "Titre ou contenu manquant\n";
            exit;
        }

        $result = $post->ajouterArticle($titre, $contenu, $_SESSION['user_id']);

        echo "Article ajouté avec succès\n";
        break;

    // ========================= MODIFIER UN ARTICLE =========================
    case 'modifier':
        $id = $_POST['id'] ?? null;
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';

        if (!$id || empty($titre) || empty($contenu)) {
            echo "ID, titre ou contenu manquant\n";
            exit;
        }

        $post->modifierArticle($id, $titre, $contenu);
        echo "Article modifié avec succès\n";
        break;

    // ========================= SUPPRIMER UN ARTICLE =========================
    case 'supprimer':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo "ID manquant\n";
            exit;
        }

        $post->supprimerArticle($id);
        echo "Article supprimé avec succès\n";
        break;

    // ========================= VOIR TOUS LES ARTICLES =========================
    case 'voir':
        $articles = $post->voirArticles();
        echo "['success' => true, 'articles' => $articles]\n";
        break;

    // ========================= RECHERCHER UN ARTICLE =========================
    case 'rechercher':
        $motCle = $_GET['motCle'] ?? '';
        if (empty($motCle)) {
            echo "Mot-clé manquant\n";
            exit;
        }

        $result = $post->rechercherArticle($motCle);
        echo "['success' => true, 'resultats' => $result]\n";
        break;

    // ========================= ACTION NON RECONNUE =========================
    default:
        echo "Action non reconnue\n";
        break;
}
