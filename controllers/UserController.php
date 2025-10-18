<?php

require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private User $user;

    public function __construct($connexion)
    {
        $this->user = new User($connexion);
    }

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

            if ($remember) {
                setcookie('remember_email', $email, time() + 30 * 24 * 3600, '/', '', false, true);
            } else {
                setcookie('remember_email', '', time() - 3600, '/', '', false, true);
            }

            return ['success' => true, 'role' => $_SESSION['user']['role']];
        }

        return ['success' => false, 'message' => 'Email ou mot de passe incorrect !'];
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: index.php?route=public/home');
        exit;
    }

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

    /**
     * 🔹 Affiche la gestion des utilisateurs (admin/manage_users)
     */
    public function manageUsers()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?route=admin/login');
            exit;
        }

        $users = $this->user->getAllUsers(); // à créer dans ton modèle si pas encore fait
        require_once __DIR__ . '/../views/admin/manage_users.php';
    }
}
