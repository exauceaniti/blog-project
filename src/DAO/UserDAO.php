<?php

namespace App\DAO;

use App\Core\Database\Database;
use App\Entity\User;
use PDO;

/**
 * Classe qui gcomminuque directement avec la base de donee.  
 * cette classe execute les requettes sql et recupere les informations dans la base de donnee
 * le transforme sous formes des object pour les utiliser apres.
 */
class UserDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère tous les utilisateurs
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM utilisateurs");
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetchAll();
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetch() ?: null;
    }

    /**
     * Récupère un utilisateur par son email
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetch() ?: null;
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function save(User $user): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO utilisateurs (nom, email, password, role)
            VALUES (:nom, :email, :password, :role)
        ");
        return $stmt->execute([
            'nom' => $user->nom,
            'email' => $user->email,
            'password' => $user->password,
            'role' => $user->role
        ]);
    }

    /**
     * Met à jour les informations d’un utilisateur et son mot de passe aussi bien sur. 
     */
    public function update(User $user): bool
    {
        $sql = "UPDATE utilisateurs SET nom = :nom, email = :email, role = :role";
        $params = [
            'nom' => $user->nom,
            'email' => $user->email,
            'role' => $user->role,
            'id' => $user->id
        ];

        // Si un mot de passe est défini, on l’ajoute à la requête
        if (!empty($user->password)) {
            $sql .= ", password = :password";
            $params['password'] = $user->password;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprime un utilisateur par son ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM utilisateurs WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
