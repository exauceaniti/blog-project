<?php

namespace Src\Factory;

use Src\Entity\User;

/**
 * Factory pour la création d'objets User
 * 
 * Cette classe fournit des méthodes statiques pour créer des instances
 * de User à partir de différentes sources de données
 */
class UserFactory
{
    /**
     * Crée un utilisateur à partir des données d'un formulaire (Inscription ou Register)
     * - Hash le mot de passe pour la sécurité
     * - Définit un rôle par défaut = 'user'
     * 
     * @param array $data Données du formulaire contenant 'nom', 'email', 'password' et optionnellement 'role'
     * @return User Instance de User hydratée avec les données du formulaire
     */
    public static function createFromFormuler(array $data): User
    {
        $user = new User();
        $user->nom = $data['nom'] ?? '';
        $user->email = $data['email'] ?? '';

        // Hash le mot de passe directement
        $user->password = isset($data['password'])
            ? password_hash($data['password'], PASSWORD_BCRYPT)
            : '';

        // Role par défaut
        $user->role = isset($data['role']) && in_array($data['role'], ['user', 'admin'])
            ? $data['role']
            : 'user';

        return $user;
    }

    /**
     * Crée un utilisateur à partir des données venant de la base de données
     * Pas de hash du mot de passe car déjà hashé en base de données
     * 
     * @param array $data Données de la base de données
     * @return User Instance de User hydratée avec les données de la BDD
     */
    public static function creatFromDatabase(array $data): user
    {
        return new User($data);
    }

    /**
     * Crée directement un administrateur
     * 
     * @param array $data Données du formulaire contenant 'nom', 'email', 'password'
     * @return User Instance de User avec le rôle 'admin'
     */
    public static function creatAdmin(array $data): User
    {
        $data['role'] = 'admin';
        return self::createFromFormuler($data);
    }
}
