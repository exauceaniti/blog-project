<?php

/**
 * Classe Commentaire
 *
 * Cette classe gère les commentaires des articles du blog :
 * création, suppression et récupération des commentaires.
 */
class commentaire
{
    /**
     * @var Connexion $conn Objet de connexion à la base de données
     */
    private $conn;

    /**
     * Constructeur
     *
     * Initialise la classe Comment avec un objet Connexion.
     *
     * @param Connexion $connexion Objet de connexion à la base de données
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    /**
     * Ajouter un commentaire
     *
     * Insère un nouveau commentaire dans la base de données pour un article donné.
     *
     * @param string $contenu Contenu du commentaire
     * @param int $articleId ID de l'article concerné
     * @param int $auteurId ID de l'auteur du commentaire
     * @return PDOStatement Résultat de l'insertion
     */
    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, date_Commentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $auteurId]);
    }

    /**
     * Supprimer un commentaire
     *
     * Supprime un commentaire de la base de données selon son ID.
     *
     * @param int $id ID du commentaire à supprimer
     * @return PDOStatement Résultat de la suppression
     */
    public function supprimerCommentaire($id)
    {
        $sql = "DELETE FROM commentaires WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }

    /**
     * Voir les commentaires d'un article
     *
     * Récupère tous les commentaires d'un article avec le nom de l'auteur,
     * triés du plus récent au plus ancien.
     *
     * @param int $articleId ID de l'article
     * @return array Tableau associatif contenant les commentaires
     */
    public function voirCommentaires($articleId)
    {
        $sql = "SELECT c.*, u.nom AS auteur
                FROM commentaires c
                JOIN utilisateurs u ON c.auteur_id = u.id
                WHERE article_id = ?
                ORDER BY dateCommentaire DESC";
        return $this->conn->executerRequete($sql, [$articleId])->fetchAll();
    }
}
