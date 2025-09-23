<?php

/**
 * Classe User - Gestion complète des utilisateurs du blog
 *
 * Cette classe centralise toutes les opérations liées aux utilisateurs :
 * inscription, connexion, déconnexion, création d'articles et de commentaires.
 * Elle interagit avec la base de données via l'objet Connexion fourni.
 *
 * @package Blog
 * @author Exauce Aniti
 * @version 1.0
 */
class User
{
    /**
     * @var Connexion $conn Instance de connexion à la base de données
     * @access private
     */
    private $conn;

    /**
     * Constructeur de la classe User
     *
     * Initialise l'objet User avec une connexion à la base de données.
     * Cette connexion sera utilisée pour toutes les opérations ultérieures.
     *
     * @param Connexion $connexion Objet de connexion à la base de données
     * @return void
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    /**
     * Inscription d'un nouvel utilisateur
     *
     * Cette méthode permet d'enregistrer un nouvel utilisateur dans le système.
     * Elle vérifie d'abord si l'email n'est pas déjà utilisé, puis hash le mot de passe
     * avant l'insertion dans la base de données.
     *
     * @param string $email Adresse email de l'utilisateur (doit être unique)
     * @param string $password Mot de passe en clair (sera hashé automatiquement)
     * @param string|null $nom Nom de l'utilisateur (optionnel, "Utilisateur" par défaut)
     * @param string $role Rôle de l'utilisateur (optionnel, "user" par défaut)
     * @return bool True si l'inscription réussit, False si l'email est déjà utilisé
     *
     * @example
     * $user->sInscrire('test@email.com', 'monpassword', 'Jean Dupont');
     */
    public function sInscrire($email, $password, $nom = null, $role = 'user')
    {
        // Vérification de l'unicité de l'email
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->conn->executerRequete($sql, [$email]);
        if ($stmt->fetch()) {
            return false; // Email déjà existant
        }

        // Valeur par défaut pour le nom si non fourni
        $nom = !empty($nom) ? $nom : 'Utilisateur';

        // Hashage sécurisé du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertion du nouvel utilisateur
        $sql = "INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)";
        $this->conn->executerRequete($sql, [$nom, $email, $hashedPassword, $role]);

        return true;
    }

    /**
     * Connexion d'un utilisateur
     *
     * Authentifie un utilisateur en vérifiant son email et mot de passe.
     * Si les identifiants sont valides, une session est démarrée et les informations
     * utilisateur sont stockées dans la session.
     *
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe en clair
     * @return array|bool Retourne les infos utilisateur si succès, False si échec
     *
     * @example
     * $userInfo = $user->seConnecter('test@email.com', 'monpassword');
     * if ($userInfo) {
     *     echo "Bienvenue " . $userInfo['nom'];
     * }
     */
    public function seConnecter($email, $password)
    {
        // Recherche de l'utilisateur par email
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->conn->executerRequete($sql, [$email]);
        $user = $stmt->fetch();

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {

            // Mise à jour du hash si nécessaire (algorithme obsolète)
            if (password_needs_rehash($user['password'], PASSWORD_BCRYPT)) {
                $newHashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $updateSql = "UPDATE utilisateurs SET password = ? WHERE id = ?";
                $this->conn->executerRequete($updateSql, [$newHashedPassword, $user['id']]);
            }

            // Sécurisation de la session
            session_regenerate_id(true);

            // Stockage des infos utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'] ?? 'user';

            return $user; // Retourne toutes les infos utilisateur
        }

        return false; // Échec de l'authentification
    }

    /**
     * Vérifie si un utilisateur est connecté
     *
     * Cette méthode checke la présence de l'ID utilisateur dans la session.
     * Utile pour protéger l'accès aux pages nécessitant une authentification.
     *
     * @return bool True si utilisateur connecté, False sinon
     *
     * @example
     * if (!$user->estConnecte()) {
     *     header('Location: login.php');
     *     exit;
     * }
     */
    public function estConnecte()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Création d'un nouvel article
     *
     * Permet à un utilisateur connecté de créer un article.
     * L'auteur_id est automatiquement récupéré depuis la session.
     *
     * @param string $titre Titre de l'article
     * @param string $contenu Contenu textuel de l'article
     * @return PDOStatement Résultat de la requête d'insertion
     *
     * @throws Exception Si aucun utilisateur n'est connecté
     *
     * @example
     * $user->creeArticle('Mon premier article', 'Contenu de mon article...');
     */
    public function creeArticle($titre, $contenu)
    {
        // Vérification implicite de la connexion via $_SESSION['user_id']
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, date_Publication) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $_SESSION['user_id']]);
    }

    /**
     * Création d'un commentaire sur un article
     *
     * Ajoute un commentaire à un article spécifique.
     * L'auteur du commentaire est l'utilisateur actuellement connecté.
     *
     * @param string $contenu Texte du commentaire
     * @param int $articleId ID de l'article à commenter
     * @return PDOStatement Résultat de la requête d'insertion
     *
     * @throws Exception Si aucun utilisateur n'est connecté
     *
     * @example
     * $user->creeCommentaire('Super article !', 15);
     */
    public function creeCommentaire($contenu, $articleId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, date_Commentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $_SESSION['user_id']]);
    }

    /**
     * Déconnexion de l'utilisateur
     *
     * Termine la session en cours et nettoie les données de session.
     * À appeler pour permettre à l'utilisateur de se déconnecter proprement.
     *
     * @return void
     *
     * @example
     * $user->seDeconnecter();
     * header('Location: index.php');
     */
    public function seDeconnecter()
    {
        session_destroy();
    }
}
