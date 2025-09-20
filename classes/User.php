<?php

/**
 * Classe User
 *
 * Cette classe gère les utilisateurs du blog : connexion, déconnexion,
 * création d'articles et création de commentaires.
 */
class User
{
    /**
     * @var Connexion $conn Objet de connexion à la base de données
     */
    private $conn;

    /**
     * Constructeur
     *
     * Initialise la classe User avec un objet Connexion.
     *
     * @param Connexion $connexion Objet de connexion à la base de données
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    /**
     * Inscription d'un utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe en clair
     * @return bool True si l'inscription est réussie, False sinon
     */
    public function sInscrire($email, $password)
    {
        // Vérifier si l'email est déjà utilisé
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->conn->executerRequete($sql, [$email]);
        if ($stmt->fetch()) {
            return false; // Email déjà utilisé
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérer le nouvel utilisateur
        $nom = $_POST['nom'] ?? 'Utilisateur';
        $role = $_POST['role'] ?? 'user'; // role user par défaut
        $sql = "INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)";
        $this->conn->executerRequete($sql, [$nom, $email, $hashedPassword, $role]);
        return true;
    }

    /**
     * Connexion d'un utilisateur
     *
     * Vérifie l'email et le mot de passe et démarre une session si valide.
     *
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe en haché
     * @return bool True si connexion réussie, False sinon
     */
    public function seConnecter($email, $password)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->conn->executerRequete($sql, [$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if (password_needs_rehash($user['password'], PASSWORD_BCRYPT)) {
                // Re-hacher le mot de passe si nécessaire
                $newHashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $updateSql = "UPDATE utilisateurs SET password = ? WHERE id = ?";
                $this->conn->executerRequete($updateSql, [$newHashedPassword, $user['id']]);
            }
            // Démarrer la session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'] ?? 'user'; // role user par défaut
            return true;
        }
        return false;
    }

    /**
     * Vérifier la connexion de l'utilisateur
     * @return bool True si l'utilisateur est connecté, False sinon
     */
    public function estConnecte()
    {
        return isset($_SESSION['user_id']);
    }


    /**
     * Création d'un article
     *
     * Ajoute un nouvel article à la base de données en utilisant
     * l'ID de l'utilisateur actuellement connecté.
     *
     * @param string $titre Titre de l'article
     * @param string $contenu Contenu de l'article
     * @return PDOStatement Résultat de l'insertion
     */
    public function creeArticle($titre, $contenu)
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, date_Publication) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $_SESSION['user_id']]);
    }

    /**
     * Création d'un commentaire
     *
     * Ajoute un nouveau commentaire pour un article donné
     * en utilisant l'ID de l'utilisateur actuellement connecté.
     *
     * @param string $contenu Contenu du commentaire
     * @param int $articleId ID de l'article sur lequel commenter
     * @return PDOStatement Résultat de l'insertion
     */
    public function creeCommentaire($contenu, $articleId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, date_Commentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $_SESSION['user_id']]);
    }

    /**
     * Déconnexion de l'utilisateur
     *
     * Détruit la session courante.
     */
    public function seDeconnecter()
    {
        session_destroy();
    }
}
