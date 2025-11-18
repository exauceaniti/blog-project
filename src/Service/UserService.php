<?php
namespace Src\Service;

use Src\Factory\UserFactory;
use Src\DAO\UserDAO;
use Src\Entity\User;

class UserService {
    private UserDAO $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    /**
     * Enregistre ou cree un nouvel utilisateur
     *
     * @param array $data Données brutes (ex. $_POST)
     * @return bool
     */
    public function register(array $data): bool {
        // Validation minimale
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->userDAO->findByEmail($data['email'])) {
            return false; // Email déjà utilisé
        }

        $user = UserFactory::create($data);
        return $this->userDAO->save($user);
    }

    /**
     * Authentifie un utilisateur
     *
     * @param string $email
     * @param string $password
     * @return ?User
     */
    public function login(string $email, string $password): ?User {
        $user = $this->userDAO->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Promeut un utilisateur au rôle admin
     *
     * @param int $id
     * @return bool
     */
    public function promoteToAdmin(int $id): bool {
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
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool {
        return $this->userDAO->delete($id);
    }

    /**
     * Récupère un utilisateur par son ID
     *
     * @param int $id
     * @return ?User
     */
    public function getUserById(int $id): ?User {
        return $this->userDAO->findById($id);
    }

    /**
     * Récupère tous les utilisateurs
     *
     * @return array Liste d'objets User
     */
    public function getAllUsers(): array {
        return $this->userDAO->findAll();
    }

    /**
     * Met à jour les informations d’un utilisateur
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser(int $id, array $data): bool {
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
