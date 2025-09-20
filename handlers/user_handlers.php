<?php

// Inclusion des classes nécessaires
require_once  '../classes/connexion.php';
require_once '../classes/User.php';
/**
 * Initialisation de la connexion à la base de données
 */
$connexion = new Connexion();
$conn = $connexion->connecter(); // on récupère l'objet PDO

// Création de l'objet User pour utiliser les méthodes utilisateur
$user = new User($connexion);

/**
 * On va détecter l'action à effectuer selon le paramètre 'action' passé en POST
 * Les actions possibles : 'inscription', 'connexion', 'deconnexion'
 */
$action = $_POST['action'] ?? null;

if ($action === 'connexion') {
    // ========================= CONNEXION =========================
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["error" => "Email ou mot de passe manquant !"]);
        exit;
    }

    // Appel de la méthode seConnecter de la classe User
    $result = $user->seConnecter($email, $password);

    if ($result) {
        echo json_encode(["success" => "Connexion réussie !"]);
    } else {
        echo json_encode(["error" => "Email ou mot de passe incorrect\n"]);
    }
} elseif ($action === 'deconnexion') {
    // ========================= DECONNEXION =========================
    $user->seDeconnecter();
    echo json_encode(["success" => "Déconnexion réussie !"]);
} else {
    // ========================= ACTION INVALIDE =========================
    echo json_encode(["error" => "Action non reconnue\n"]);
}

// ========================= INSCRIPTION =========================
if ($action === 'inscription') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["error" => "Email ou mot de passe manquant !"]);
        exit;
    }

    // Appel de la méthode sInscrire de la classe User
    $result = $user->sInscrire($email, $password);

    if ($result) {
        echo json_encode(["success" => "Inscription réussie !"]);
    } else {
        echo json_encode(["error" => "Email déjà utilisé\n"]);
    }
}
