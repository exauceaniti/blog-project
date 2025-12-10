<?php

namespace Src\Core\Auth;

use Src\Service\UserService;
use Src\Entity\User; // Import pour le type-hinting

class Authentification
{
    /**
     * Tente de connecter l’utilisateur
     * * Cette méthode utilise le UserService pour vérifier les identifiants.
     * Si succès, elle enregistre l'utilisateur en session.
     */
    public static function login(UserService $userService, string $email, string $password): ?User
    {
        // Le Service gère la recherche et la vérification du mot de passe
        $user = $userService->login($email, $password);

        if ($user instanceof User) {
            self::setConnectedUser($user); // Enregistre l'Entité en session
            return $user;
        }

        return null;
    }

    /**
     * Enregistre l'objet User en session pour marquer l'utilisateur comme connecté.
     */
    public static function setConnectedUser(User $user): void
    {
        // Stockage des informations essentielles
        $_SESSION['user'] = [
            'id'    => $user->id,
            'nom'   => $user->nom,
            'email' => $user->email,
            'role'  => $user->role ?? 'user'
        ];
    }

    /**
     * Vérifie si l'utilisateur est actuellement connecté.
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['id']);
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     */
    public static function getUserId(): ?int
    {
        if (self::isLoggedIn()) {
            return $_SESSION['user']['id'];
        }
        return null;
    }

    /**
     * Déconnecte l’utilisateur
     */
    public static function logout(): void
    {
        unset($_SESSION['user']);
        // session_destroy() est souvent appelé au niveau du contrôleur ou du front controller
        // pour s'assurer que toutes les sessions sont nettoyées.
    }
}
