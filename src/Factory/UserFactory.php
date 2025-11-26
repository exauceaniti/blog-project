<?php

namespace Src\Factory;

use Src\Entity\User;

class UserFactory
{
    /**
     * Cree un utilisateur a partir des donnee du formulaires (Inscription ou Register)
     * -Hash le mot de passe pour la securite
     * -Role par defaut = user
     */

    public static function createFromForm(array $data): User
    {
        $user = new User();
        $user->nom = $data['nom'] ?? '';
        $user->email = $data['email'] ?? '';

        //hashe le mot de passe directement
        $user->password = isset($data['password'])
            ? password_hash($data['password'], PASSWORD_BCRYPT)
            : '';

        //Role par defaut
        $user->role = isset($data['role']) && in_array($data['role'], ['user', 'admin'])
            ? $data['role']
            : 'user';

        return $user;
    }

    /**
     * Cree un utilisateur a partir ds donnees venant de la base de donnee
     * pas de hash car deja sortie de la,  il est hacher.
     */

    public static function creatFromDatabase(array $data): user
    {
        return new User($data);
    }

    /**
     * Cree directement unadministrateur.
     */
    public static function creatAdmin(array $data): User
    {
        $data['role'] = 'admin';
        return self::creatAdmin($data);
    }
}
