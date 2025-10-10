<?php
class Commentaire
{
    private $conn;

    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, date_Commentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $auteurId]);
    }

    public function supprimerCommentaire($id)
    {
        $sql = "DELETE FROM commentaires WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }

    public function voirCommentaires($articleId)
    {
        $sql = "SELECT c.*, u.nom AS auteur
        FROM commentaires c
        JOIN utilisateurs u ON c.auteur_id = u.id
        WHERE c.article_id = ?
        ORDER BY c.date_Commentaire DESC";
        return $this->conn->executerRequete($sql, [$articleId])->fetchAll();
    }

    /**
     * Compter tous les commentaires
     * @return int - Nombre total de commentaires
     */
    public function countAllComments()
    {
        $sql = "SELECT COUNT(*) AS total FROM commentaires";
        $stmt = $this->conn->executerRequete($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}