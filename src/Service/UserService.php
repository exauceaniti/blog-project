<?php

namespace Src\Service;

use Src\Factory\UserFactory;
use Src\DAO\UserDAO;
use Src\Entity\User;

/**
 * Service de gestion des utilisateurs
 * * Cette classe contient la logique métier pour les opérations liées aux utilisateurs
 * Elle fait le lien entre les contrôleurs, la factory et le DAO
 */
class UserService
{
    private UserDAO $userDAO;

    public function __construct()
    {
        // NOTE: Idéalement, le DAO devrait être injecté via un Container
        $this->userDAO = new UserDAO();
    }

    /**
     * Enregistre ou crée un nouvel utilisateur
     */
    public function register(array $data): bool
    {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Vérification d'unicité via le DAO
        if ($this->userDAO->findByEmail($data['email'])) {
            return false; // Email déjà utilisé
        }

        // Création de l'Entité via la Factory
        $user = UserFactory::createFromFormuler($data);
        return $this->userDAO->save($user);
    }

    /**
     * Authentifie un utilisateur
     * - Recherche l'utilisateur par email via le DAO
     * - Vérifie le mot de passe
     *
     * @return User|null Instance de User si authentification réussie, null sinon
     */
    public function login(string $email, string $password): ?User
    {
        // Le DAO est supposé retourner une Entité User (ou un objet avec accès aux propriétés)
        $user = $this->userDAO->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Récupère un utilisateur par son email (méthode utilitaire)
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->userDAO->findByEmail($email);
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getUserById(int $id): ?User
    {
        return $this->userDAO->findById($id);
    }

    // ... (Reste des méthodes : promoteToAdmin, deleteUser, getAllUsers, updateUser) ...
}
