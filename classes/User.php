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
     * Connexion d'un utilisateur
     *
     * Vérifie l'email et le mot de passe et démarre une session si valide.
     *
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe en clair
     * @return bool True si connexion réussie, False sinon
     */
    public function seConnecter($email, $password)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->conn->executerRequete($sql, [$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        return false;
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
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, datePublication) VALUES (?, ?, ?, NOW())";
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
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, dateCommentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $_SESSION['user_id']]);
    }
}
