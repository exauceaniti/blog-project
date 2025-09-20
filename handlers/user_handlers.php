<?php
// Démarrage de la session pour gérer l'état de l'utilisateur
session_start();

// Inclusion des classes nécessaires
require_once  '../config/database.php';
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

if ($action === 'inscription') {
    // ========================= INSCRIPTION =========================
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Email ou mot de passe manquant !\n";
        exit;
    }

    // Appel de la méthode sInscrire de la classe User
    $result = $user->sInscrire($email, $password);

    if ($result) {
        echo "Inscription réussie !\n";
    } else {
        echo "Cet email est déjà utilisé\n";
    }
} elseif ($action === 'connexion') {
    // ========================= CONNEXION =========================
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Email ou mot de passe manquant !\n";
        exit;
    }

    // Appel de la méthode seConnecter de la classe User
    $result = $user->seConnecter($email, $password);

    if ($result) {
        echo "connexion réussie !\n";
    } else {
        echo "Email ou mot de passe incorrect\n";
    }
} elseif ($action === 'deconnexion') {
    // ========================= DECONNEXION =========================
    $user->seDeconnecter();
    echo "Déconnexion réussie !\n";
} else {
    // ========================= ACTION INVALIDE =========================
    echo "Action non reconnue\n";
}
