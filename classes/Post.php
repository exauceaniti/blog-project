<?php
class Post
{
    private $conn;

    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    public function ajouterArticle($titre, $contenu, $auteurId)
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, datePublication) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $auteurId]);
    }

    public function modifierArticle($id, $titre, $contenu)
    {
        $sql = "UPDATE articles SET titre = ?, contenu = ? WHERE id = ?";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $id]);
    }

    public function supprimerArticle($id)
    {
        $sql = "DELETE FROM articles WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }

    public function voirArticle()
    {
        $sql = "SELECT a.*, u.nom AS auteur FROM articles a JOIN utilisateurs u ON a.auteur_id = u.id ORDER BY datePublication DESC";
        return $this->conn->executerRequete($sql)->fetchAll();
    }
}
