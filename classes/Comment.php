<?php
class Comment
{
    private $conn;

    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, dateCommentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $auteurId]);
    }

    public function supprimerCommentaire($id)
    {
        $sql = "DELETE FROM commentaires WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }

    public function voirCommentaires($articleId)
    {
        $sql = "SELECT c.*, u.nom AS auteur FROM commentaires c JOIN utilisateurs u ON c.auteur_id = u.id WHERE article_id = ? ORDER BY dateCommentaire DESC";
        return $this->conn->executerRequete($sql, [$articleId])->fetchAll();
    }
}
