<?php

class User
{
    private $conn;

    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    // ========================= UTILISATEUR =========================

    /**
     * Récupérer un utilisateur par email
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT id, nom, email, password, role FROM utilisateurs WHERE email = ?";
        $stmt = $this->conn->executerRequete($sql, [$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function isAdmin($userId): bool
    {
        $sql = "SELECT role FROM utilisateurs WHERE id = ?";
        $stmt = $this->conn->executerRequete($sql, [$userId]);
        $result = $stmt->fetch();
        return $result && $result['role'] === 'admin';
    }

    public function getUserById($id)
    {
        $sql = "SELECT id, nom, email, role FROM utilisateurs WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }




    /**
     * Inscription d'un utilisateur
     */
    public function sInscrire($email, $password, $nom = null, $role = 'user')
    {
        $stmt = $this->conn->executerRequete("SELECT * FROM utilisateurs WHERE email = ?", [$email]);
        if ($stmt->fetch())
            return false;

        $nom = $nom ?: 'Utilisateur';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)";
        $this->conn->executerRequete($sql, [$nom, $email, $hashedPassword, $role]);

        return true;
    }

    /**
     * Connexion utilisateur
     */
    public function seConnecter($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }



    /**
     * Vérifie si un utilisateur est connecté
     */
    public function estConnecte(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Déconnexion utilisateur
     */
    public function seDeconnecter()
    {
        session_destroy();
    }

    // ========================= GESTION UTILISATEURS =========================

    /**
     * Récupérer tous les utilisateurs
     */
    public function voirUtilisateurs(): array
    {
        $sql = "SELECT id, nom, email, role FROM utilisateurs ORDER BY id DESC";
        return $this->conn->executerRequete($sql)->fetchAll();
    }

    /**
     * Compter tous les utilisateurs
     * @return int
     */
    public function countAllUsers(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM utilisateurs";
        $stmt = $this->conn->executerRequete($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Modifier le rôle d'un utilisateur
     */
    public function changerRole($id, $nouveauRole)
    {
        $sql = "UPDATE utilisateurs SET role = ? WHERE id = ?";
        $this->conn->executerRequete($sql, [$nouveauRole, $id]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function supprimerUtilisateur($id)
    {
        $sql = "DELETE FROM utilisateurs WHERE id = ?";
        $this->conn->executerRequete($sql, [$id]);
    }

    // ========================= ARTICLES ET COMMENTAIRES =========================

    /**
     * Créer un article pour l'utilisateur connecté
     */
    public function creeArticle($titre, $contenu)
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, date_publication) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $_SESSION['user']['id']]);
    }

    /**
     * Créer un commentaire pour l'utilisateur connecté
     */
    public function creeCommentaire($contenu, $articleId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, date_commentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $_SESSION['user']['id']]);
    }

    /**
     * Récupérer tous les utilisateurs
     */

    public function getAllUsers()
    {
        $sql = "SELECT id, nom, email, role FROM utilisateurs ORDER BY id DESC";
        return $this->conn->executerRequete($sql)->fetchAll();
    }

}
