<?php

namespace App\Core\Session;

use App\Entity\User;

class SessionService
{

    public static function startIfNotStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setUser(User $user): void
    {
        self::startIfNotStarted();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_nom'] = $user->nom;
    }

    //Recupere le role pour son utilisation dans le Midleware.
    public static function getRole(): string
    {
        return $_SESSION['user_role'] ?? '';
    }

    // Utile pour la sécurité dans AdminController
    public static function isLoggedIn(): bool
    {
        self::startIfNotStarted();
        return isset($_SESSION['user_id']);
    }

    // Utile pour la sécurité dans AdminController
    public static function isAdmin(): bool
    {
        self::startIfNotStarted();
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function destroy(): void
    {
        self::startIfNotStarted();
        session_unset();
        session_destroy();
    }
}
