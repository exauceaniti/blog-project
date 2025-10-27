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

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            return ['view' => 'public/login', 'data' => ['error' => 'Champs obligatoires']];
        }

        $user = $this->user->seConnecter($email, $password);

        if ($user) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            if ($remember) {
                setcookie('remember_email', $email, time() + 30 * 24 * 3600, '/', '', false, true);
            }

            return ['redirect' => $user['role'] === 'admin' ? 'admin/dashboard' : 'public/home'];
        }

        return ['view' => 'public/login', 'data' => ['error' => 'Email ou mot de passe incorrect']];
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
            return ['redirect' => 'admin/login'];
        }

        $users = $this->user->getAllUsers();
        return ['view' => 'admin/manage_users', 'data' => ['users' => $users]];
    }

    public function deleteUser($id)
    {
        $this->user->supprimerUtilisateur($id);
        return ['redirect' => 'admin/manage_users'];
    }

    public function changeUserRole($id, $newRole)
    {
        $this->user->changerRole($id, $newRole);
        return ['redirect' => 'admin/manage_users'];
    }
}
