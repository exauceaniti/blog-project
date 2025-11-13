<?php
namespace Src\Factory;

use Src\Entity\User;

class UserFactory {

    /**
     * Crée un utilisateur standard à partir des données du formulaire
     *
     * @param array $data Données brutes (ex. $_POST)
     * @return User
     */
    public static function create(array $data): User {
        $user = new User();

        // Affectation des champs
        $user->nom = $data['nom'] ?? '';
        $user->email = $data['email'] ?? '';
        
        // Hash du mot de passe
        $user->password = isset($data['password']) 
            ? password_hash($data['password'], PASSWORD_BCRYPT)
            : '';

        // Rôle 'user'par défaut
        $user->role = isset($data['role']) && in_array($data['role'], ['user', 'admin']) 
            ? $data['role'] 
            : 'user';

        return $user;
    }

    /**
     * Crée un utilisateur administrateur a faire apres la dans la partie de l'dmin.
     *
     * @param array $data
     * @return User
     */
    public static function createAdmin(array $data): User {
        $data['role'] = 'admin';
        return self::create($data);
    }
}
