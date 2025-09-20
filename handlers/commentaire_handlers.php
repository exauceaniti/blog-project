<?php
// ========================== COMMENTAIRE HANDLER ==========================
// Ce fichier gère toutes les actions liées aux commentaires côté serveur :
// - Ajouter un commentaire
// - Supprimer un commentaire
// - Voir tous les commentaires d'un article
// Les messages sont affichés directement dans le terminal pour les tests.

// Démarrage de la session pour gérer l'utilisateur connecté
session_start();

// ========================== INCLUSIONS ==========================
// Connexion à la base et classes nécessaires
require_once '../config/database.php';
require_once '../classes/connexion.php';
require_once '../classes/commentaire.php';

// ========================== INITIALISATION ==========================
// Connexion à la base de données
$connexion = new Connexion();
$conn = $connexion->connecter(); // PDO
$comment = new Commentaire($connexion);

// Simulation d'un utilisateur connecté pour tests
// Dans un vrai projet, $_SESSION['user_id'] est défini après login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 4; // ID d'un utilisateur existant pour tests
}

// ========================== RÉCUPÉRATION DE L'ACTION ==========================
// L'action peut être 'ajouter', 'supprimer' ou 'voir'
$action = $_POST['action'] ?? null;

// ========================== GESTION DES ACTIONS ==========================
switch ($action) {

    // ========================= AJOUTER UN COMMENTAIRE =========================
    case 'ajouter':
        $articleId = $_POST['articleId'] ?? null; // ID de l'article ciblé
        $contenu = $_POST['contenu'] ?? '';       // Contenu du commentaire

        // Vérification des champs obligatoires
        if (!$articleId || empty($contenu)) {
            echo "Article ID ou contenu manquant\n";
            exit;
        }

        // Appel de la méthode ajouterCommentaire de la classe Commentaire
        $comment->ajouterCommentaire($contenu, $articleId, $_SESSION['user_id']);
        echo "Commentaire ajouté avec succès\n";
        break;

    // ========================= SUPPRIMER UN COMMENTAIRE =========================
    case 'supprimer':
        $id = $_POST['id'] ?? null; // ID du commentaire à supprimer

        // Vérification de l'ID
        if (!$id) {
            echo "ID manquant\n";
            exit;
        }

        // Appel de la méthode supprimerCommentaire
        $comment->supprimerCommentaire($id);
        echo "Commentaire supprimé avec succès\n";
        break;

    // ========================= VOIR LES COMMENTAIRES D'UN ARTICLE =========================
    case 'voir':
        $articleId = $_POST['articleId'] ?? null;

        // Vérification de l'ID de l'article
        if (!$articleId) {
            echo "Article ID manquant\n";
            exit;
        }

        // Récupération des commentaires
        $commentaires = $comment->voirCommentaires($articleId);

        // Affichage côté terminal
        echo "Commentaires pour l'article $articleId :\n";
        print_r($commentaires);
        break;

    // ========================= CAS ACTION INVALIDE =========================
    default:
        echo "Action non reconnue\n";
        break;
}
