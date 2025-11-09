<?php

namespace Core\Session;

/**
 * Classe UserContext
 * 
 * Centralise l’accès aux informations de session utilisateur
 */
class UserContext
{
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
     * Vérifie si l’utilisateur est un utilisateur normal
     */
    public static function isUser(): bool
    {
        return $_SESSION['user']['role'] ?? '' === 'user';
    }

    /**
     * Retourne l’ID de l’utilisateur connecté
     */
    public static function getId(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Retourne le nom de l’utilisateur connecté
     */
    public static function getName(): ?string
    {
        return $_SESSION['user']['nom'] ?? null;
    }

    /**
     * Retourne l’email de l’utilisateur connecté
     */
    public static function getEmail(): ?string
    {
        return $_SESSION['user']['email'] ?? null;
    }

    /**
     * Retourne toutes les infos de l’utilisateur
     */
    public static function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}
