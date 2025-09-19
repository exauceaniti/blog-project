<?php

/**
 * Classe Post
 *
 * Cette classe gère les opérations CRUD (Créer, Lire, Mettre à jour, Supprimer)
 * pour les articles d’un blog. Elle utilise un objet Connexion pour interagir
 * avec la base de données.
 */
class Post
{
    /**
     * @var Connexion $conn Objet de connexion à la base de données
     */
    private $conn;

    /**
     * Constructeur
     *
     * Initialise la classe Post avec un objet Connexion.
     *
     * @param Connexion $connexion Objet de connexion à la base de données
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    /**
     * Ajouter un nouvel article
     *
     * @param string $titre Titre de l'article
     * @param string $contenu Contenu de l'article
     * @param int $auteurId ID de l'auteur
     * @return PDOStatement Résultat de l'insertion
     */
    public function ajouterArticle($titre, $contenu, $auteurId)
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, date_Publication) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $auteurId]);
    }

    /**
     * Modifier un article existant
     *
     * @param int $id ID de l'article
     * @param string $titre Nouveau titre
     * @param string $contenu Nouveau contenu
     * @return PDOStatement Résultat de la mise à jour
     */
    public function modifierArticle($id, $titre, $contenu)
    {
        $sql = "UPDATE articles SET titre = ?, contenu = ? WHERE id = ?";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $id]);
    }

    /**
     * Supprimer un article
     *
     * @param int $id ID de l'article à supprimer
     * @return PDOStatement Résultat de la suppression
     */
    public function supprimerArticle($id)
    {
        $sql = "DELETE FROM articles WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }

    /**
     * Voir tous les articles
     *
     * Récupère tous les articles avec le nom de l'auteur.
     * Les articles sont triés du plus récent au plus ancien.
     *
     * @return array Tableau associatif contenant les articles
     */
    public function voirArticle()
    {
        $sql = "SELECT a.*, u.nom AS auteur FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                ORDER BY date_Publication DESC";
        return $this->conn->executerRequete($sql)->fetchAll();
    }
}
