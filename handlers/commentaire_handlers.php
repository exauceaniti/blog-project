<?php
// Démarrage de la session
// header('Content-Type: application/json');
session_start();

// var_dump($_POST);
// die();

// Inclusion des classes nécessaires
require_once '../config/database.php';
require_once '../classes/connexion.php';
require_once '../classes/commentaire.php';
require_once '../classes/user.php';

// Initialisation de la connexion
$connexion = new Connexion();
$conn = $connexion->connecter(); // PDO
$comment = new Commentaire($connexion);
$user = new User($connexion);

// Vérification que l'utilisateur est connecté avant toute action
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour effectuer cette action\n";
    exit;
}

// Détection de l'action
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {

    // ========================= AJOUTER UN COMMENTAIRE =========================
    case 'ajouter':
        $articleId = $_POST['articleId'] ?? null;
        $contenu = $_POST['contenu'] ?? '';

        if (!$articleId || empty($contenu)) {
            echo "Article ID ou contenu manquant\n";
            exit;
        }

        $comment->ajouterCommentaire($contenu, $articleId, $_SESSION['user_id']);
        echo "Commentaire ajouté avec succès\n";
        break;

    // ========================= SUPPRIMER UN COMMENTAIRE =========================
    case 'supprimer':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo "ID manquant\n";
            exit;
        }

        $comment->supprimerCommentaire($id);
        echo "Commentaire supprimé avec succès\n";
        break;

    // ========================= VOIR LES COMMENTAIRES D'UN ARTICLE =========================
    case 'voir':
        $articleId = $_GET['articleId'] ?? null;

        if (!$articleId) {
            echo "Article ID manquant\n";
            exit;
        }

        $commentaires = $comment->voirCommentaires($articleId);
        echo "['success' => true, 'commentaires' => $commentaires]\n";
        break;

    // ========================= ACTION NON RECONNUE =========================
    default:
        echo "Action non reconnue\n";
        break;
}
