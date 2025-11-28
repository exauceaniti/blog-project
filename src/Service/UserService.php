<?php

namespace Src\Service;

use Src\Factory\UserFactory;
use Src\DAO\UserDAO;
use Src\Entity\User;

/**
 * Service de gestion des utilisateurs
 * 
 * Cette classe contient la logique métier pour les opérations liées aux utilisateurs
 * Elle fait le lien entre les contrôleurs, la factory et le DAO
 */
class UserService
{
    /**
     * @var UserDAO Instance du DAO pour l'accès aux données des utilisateurs
     */
    private UserDAO $userDAO;

    /**
     * Constructeur de la classe UserService
     * Initialise l'instance du UserDAO
     */
    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }

    /**
     * Enregistre ou crée un nouvel utilisateur
     * - Valide l'email
     * - Vérifie l'unicité de l'email
     * - Crée l'utilisateur via la factory
     * - Sauvegarde en base de données
     *
     * @param array $data Données brutes (ex. $_POST)
     * @return bool True si l'enregistrement a réussi, false sinon
     */
    public function register(array $data): bool
    {
        // Validation minimale
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->userDAO->findByEmail($data['email'])) {
            return false; // Email déjà utilisé
        }

        $user = UserFactory::createFromFormuler($data);
        return $this->userDAO->save($user);
    }

    /**
     * Authentifie un utilisateur
     * - Recherche l'utilisateur par email
     * - Vérifie le mot de passe avec password_verify
     *
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe en clair
     * @return User|null Instance de User si authentification réussie, null sinon
     */
    public function login(string $email, string $password): ?User
    {
        $user = $this->userDAO->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Promeut un utilisateur au rôle admin
     * - Recherche l'utilisateur par ID
     * - Modifie son rôle en 'admin'
     * - Met à jour en base de données
     *
     * @param int $id ID de l'utilisateur à promouvoir
     * @return bool True si la promotion a réussi, false sinon
     */
    public function promoteToAdmin(int $id): bool
    {
        $user = $this->userDAO->findById($id);

        if (!$user) {
            return false;
        }

        $user->role = 'admin';
        return $this->userDAO->update($user);
    }

    /**
     * Supprime un utilisateur
     *
     * @param int $id ID de l'utilisateur à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function deleteUser(int $id): bool
    {
        return $this->userDAO->delete($id);
    }

    /**
     * Récupère un utilisateur par son ID
     *
     * @param int $id ID de l'utilisateur à récupérer
     * @return User|null Instance de User si trouvé, null sinon
     */
    public function getUserById(int $id): ?User
    {
        return $this->userDAO->findById($id);
    }

    /**
     * Récupère tous les utilisateurs
     *
     * @return array Liste d'objets User
     */
    public function getAllUsers(): array
    {
        return $this->userDAO->findAll();
    }

    /**
     * Met à jour les informations d'un utilisateur
     * - Recherche l'utilisateur par ID
     * - Met à jour les champs modifiés
     * - Hash le nouveau mot de passe si fourni
     * - Sauvegarde les modifications
     *
     * @param int $id ID de l'utilisateur à modifier
     * @param array $data Données à mettre à jour
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function updateUser(int $id, array $data): bool
    {
        $user = $this->userDAO->findById($id);

        if (!$user) {
            return false;
        }

        // Mise à jour des champs
        $user->nom = $data['nom'] ?? $user->nom;
        $user->email = $data['email'] ?? $user->email;

        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->userDAO->update($user);
    }
}
