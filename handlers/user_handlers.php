<?php
session_start();

// Inclusion des classes nécessaires
require_once '../classes/connexion.php';
require_once '../classes/User.php';

/**
 * Initialisation de la connexion à la base de données
 */
$connexion = new Connexion();
$conn = $connexion->connecter(); // on récupère l'objet PDO

// Création de l'objet User pour utiliser les méthodes utilisateur
$user = new User($connexion);

// Détection de l'action envoyée
$action = $_POST['action'] ?? null;

if ($action === 'connexion') {
    // ========================= CONNEXION =========================
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["error" => "Email ou mot de passe manquant !"]);
        exit;
    }

    $result = $user->seConnecter($email, $password);

    if ($result) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['email']   = $result['email'];
        $_SESSION['role']    = $result['role'] ?? 'user';

        // Redirection selon le rôle
        if ($result['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php"); // page d'accueil pour les users
        }
        exit;
    } else {
        $_SESSION['error_message'] = "Email ou mot de passe incorrect !";
        header("Location: ../login.php");
        exit;
    }
} elseif ($action === 'inscription') {
    // ========================= INSCRIPTION =========================
    $nom = $_POST['nom'] ?? 'Utilisateur';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["error" => "Email ou mot de passe manquant !"]);
        exit;
    }

    $result = $user->sInscrire($email, $password, $nom);

    if ($result) {
        echo json_encode(["success" => "Inscription réussie !"]);
    } else {
        echo json_encode(["error" => "Email déjà utilisé"]);
    }
} elseif ($action === 'deconnexion') {
    // ========================= DECONNEXION =========================
    $user->seDeconnecter();
    echo json_encode(["success" => "Déconnexion réussie !"]);
} else {
    // ========================= ACTION INVALIDE =========================
    echo json_encode(["error" => "Action non reconnue"]);
}
