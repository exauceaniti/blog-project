<?php

namespace App\Entity;

/**
 * Classe User représentant l'entité utilisateur dans le système
 * 
 * Cette classe définit la structure des données d'un utilisateur
 * avec ses propriétés et leurs caractéristiques
 */
class User
{
    /**
     * @var int|null Identifiant unique de l'utilisateur
     */
    public ?int $id = null;

    /**
     * @var string Nom de l'utilisateur
     */
    public string $nom;

    /**
     * @var string Adresse email de l'utilisateur
     */
    public string $email;

    /**
     * @var string Mot de passe hashé de l'utilisateur
     */
    public string $password;

    /**
     * @var string Rôle de l'utilisateur dans le système
     * @default 'user'
     */
    public string $role = 'user';

    /**
     * @var string|null Date d'inscription de l'utilisateur
     */
    public ?string $date_inscription = null;
}
