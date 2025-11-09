<?php

namespace Core\Auth;

use models\User;

require_once dirname(__DIR__, 2) . '/models/User.php';

class Authentification
{
    /**
     * Tente de connecter l’utilisateur
     */
    public static function login(string $email, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'user'
        ];

        return true;
    }

    /**
     * Déconnecte l’utilisateur
     */
    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    /**
     * Vérifie si un utilisateur est connecté
     */
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Vérifie si l’utilisateur est admin
     */
    public static function isAdmin(): bool
    {
        return $_SESSION['user']['role'] ?? '' === 'admin';
    }

    /**
     * Retourne les infos de l’utilisateur connecté
     */
    public static function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}
