<?php

/**
 * @file UserController.php
 * @description Contrôleur principal pour la gestion de l'authentification utilisateur
 * @author Exauce Aniti
 * @version 1.2
 * @date 2025
 */

session_start();

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
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['username'] = $result['nom'] ?? '';
            $_SESSION['email'] = $result['email'];
            $_SESSION['role'] = $result['role'] ?? 'user';

            // Cookie “Se souvenir de moi”
            if ($remember) {
                setcookie('remember_email', $email, time() + 30 * 24 * 3600, '/', '', false, true);
            } else {
                setcookie('remember_email', '', time() - 3600, '/', '', false, true);
            }

            return ['success' => true, 'role' => $_SESSION['role']];
        }

        return ['success' => false, 'message' => 'Email ou mot de passe incorrect !'];
    }

    // ========================= MÉTHODE DE DÉCONNEXION =========================
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: ../views/login.php');
        exit;
    }

    // ========================= MÉTHODE D'INSCRIPTION =========================
    public function register(string $nom, string $email, string $password): bool
    {
        $nom = trim($nom);
        $email = trim($email);
        $password = trim($password);

        if (empty($nom) || empty($email) || empty($password)) {
            $_SESSION['error_message'] = 'Tous les champs sont obligatoires !';
            return false;
        }

        return $this->user->sInscrire($email, $password, $nom);
    }

    // ========================= GESTIONNAIRE DE REQUÊTES =========================
    public function handleRequest(): void
    {
        $action = $_POST['action'] ?? null;

        switch ($action) {
            case 'connexion':
                $remember = isset($_POST['remember']);
                $loginResult = $this->login($_POST['email'] ?? '', $_POST['password'] ?? '', $remember);

                if ($loginResult['success']) {
                    // Redirection possible
                    $redirect = $_SESSION['redirect_url'] ??
                        (($loginResult['role'] === 'admin') ? '/admin/dashboard.php' : '/index.php');
                    unset($_SESSION['redirect_url']);
                    header('Location: ' . $redirect);
                } else {
                    $_SESSION['form_data']['email'] = $_POST['email'] ?? '';
                    $_SESSION['error_message'] = $loginResult['message'];
                    header('Location: ../views/login.php?error=1');
                }
                exit;

            case 'deconnexion':
                $this->logout();
                break;

            case 'inscription':
                $nom = $_POST['nom'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';

                if ($this->register($nom, $email, $password)) {
                    // Connexion automatique après inscription
                    $loginResult = $this->login($email, $password);
                    if ($loginResult['success']) {
                        $_SESSION['success_message'] = 'Félicitations ! Votre compte a été créé avec succès.';
                        header('Location: /index.php?success=1');
                    } else {
                        $_SESSION['error_message'] = 'Compte créé mais problème de connexion automatique.';
                        header('Location: ../views/login.php');
                    }
                } else {
                    $_SESSION['form_data'] = $_POST;
                    $_SESSION['error_message'] = 'Cet email est déjà utilisé ou champs invalides !';
                    header('Location: ../views/register.php?error=1');
                }
                exit;

            default:
                $_SESSION['error_message'] = 'Action non reconnue !';
                header('Location: ../views/login.php');
                exit;
        }
    }
}

// ========================= POINT D'ENTRÉE =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connexion = new Connexion();
    $controller = new UserController($connexion);
    $controller->handleRequest();
} else {
    header('Location: ../views/login.php');
    exit;
}
