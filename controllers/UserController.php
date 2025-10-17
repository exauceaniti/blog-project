<?php

/**
 * @file UserController.php
 * @description Contrôleur principal pour la gestion de l'authentification utilisateur
 * @author Exauce Aniti
 * @version 1.3
 * @date 2025
 */


require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private User $user;

    public function __construct($connexion)
    {
        $this->user = new User($connexion);
    }

    // ========================= MÉTHODE DE CONNEXION =========================
    public function login(string $email, string $password, bool $remember = false): array
    {
        $email = trim($email);
        $password = trim($password);

        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email ou mot de passe manquant !'];
        }

        $result = $this->user->seConnecter($email, $password);

        if ($result) {
            $_SESSION['user'] = [
                'id' => $result['id'],
                'nom' => $result['nom'] ?? '',
                'email' => $result['email'],
                'role' => $result['role'] ?? 'user',
            ];

            // Cookie “Se souvenir de moi”
            if ($remember) {
                setcookie('remember_email', $email, time() + 30 * 24 * 3600, '/', '', false, true);
            } else {
                setcookie('remember_email', '', time() - 3600, '/', '', false, true);
            }

            return ['success' => true, 'role' => $_SESSION['user']['role']];
        }

        return ['success' => false, 'message' => 'Email ou mot de passe incorrect !'];
    }

    // ========================= MÉTHODE DE DÉCONNEXION =========================
    public function logout(): array
    {
        session_unset();
        session_destroy();
        return ['success' => true];
    }

    // ========================= MÉTHODE D'INSCRIPTION =========================
    public function register(string $nom, string $email, string $password): array
    {
        $nom = trim($nom);
        $email = trim($email);
        $password = trim($password);

        if (empty($nom) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Tous les champs sont obligatoires !'];
        }

        $result = $this->user->sInscrire($email, $password, $nom);
        if ($result) {
            return ['success' => true, 'message' => 'Compte créé avec succès.'];
        }

        return ['success' => false, 'message' => 'Cet email est déjà utilisé !'];
    }

    // ========================= GESTIONNAIRE DE REQUÊTES =========================
    public function handleRequest(): array
    {
        $action = $_POST['action'] ?? null;

        switch ($action) {
            case 'connexion':
                $remember = isset($_POST['remember']);
                return $this->login($_POST['email'] ?? '', $_POST['password'] ?? '', $remember);

            case 'deconnexion':
                return $this->logout();

            case 'inscription':
                $nom = $_POST['nom'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                return $this->register($nom, $email, $password);

            default:
                return ['success' => false, 'message' => 'Action non reconnue !'];
        }
    }
}

// ========================= POINT D'ENTRÉE =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connexion = new Connexion();
    $controller = new UserController($connexion);
    $result = $controller->handleRequest();

    // 🔹 Retourner les résultats au format JSON pour que le routeur ou index.php gère la suite
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
