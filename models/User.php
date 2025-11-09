<?php

namespace models;

use Connexion;
use PDO;

require_once dirname(__DIR__) . '/config/connexion.php';

/**
 * Classe User
 * 
 * Gère les opérations liées aux utilisateurs : création, recherche, etc.
 */
class User
{
    /**
     * @var Connexion Instance de connexion à la base de données
     */
    private Connexion $db;

    /**
     * Constructeur
     * Initialise la connexion via le singleton Connexion
     */
    public function __construct()
    {
        $this->db = Connexion::getInstance();
    }

    /**
     * Rechercher un utilisateur par email
     */
    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        return $this->db->executerRequete($sql, [$email])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create(string $nom, string $email, string $hashedPassword, string $role = 'user'): bool
    {
        $sql = "INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->executerRequete($sql, [$nom, $email, $hashedPassword, $role]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function findById(int $id): array|false
    {
        $sql = "SELECT * FROM utilisateurs WHERE id = ?";
        return $this->db->executerRequete($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifier si un email est déjà utilisé
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== false;
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM utilisateurs WHERE id = ?";
        $stmt = $this->db->executerRequete($sql, [$id]);
        return $stmt->rowCount() > 0;
    }
}
