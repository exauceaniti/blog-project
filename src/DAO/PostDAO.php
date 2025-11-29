<?php

namespace Src\DAO;

use Src\Entity\Post;
use PDO;

class PostDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Partie de requête commune pour les SELECT (Jointure Auteur + Comptage Commentaires)
     */
    private function getBaseSelectQuery(): string
    {
        return "SELECT 
                    a.*, 
                    u.nom AS auteur_nom,
                    COUNT(c.id) AS comment_count
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                LEFT JOIN commentaires c ON a.id = c.article_id";
    }

    /**
     * Récupère tous les articles avec Auteur et Compte Commentaires
     *
     * @return Post[]
     */
    public function findAll(): array
    {
        $sql = $this->getBaseSelectQuery() . " GROUP BY a.id ORDER BY a.date_publication DESC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Post($row), $rows);
    }
    
    // NOUVELLE MÉTHODE POUR L'ACCUEIL
    /**
     * Récupère un nombre limité des derniers articles.
     *
     * @param int $limit Le nombre maximum d'articles à récupérer.
     * @return Post[]
     */
    public function findLatest(int $limit = 5): array
    {
        $sql = $this->getBaseSelectQuery() . " GROUP BY a.id ORDER BY a.date_publication DESC LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Post($row), $rows);
    }

    /**
     * Récupère un article par son ID (avec Auteur et Compte Commentaires)
     *
     * @param int $id
     * @return ?Post
     */
    public function findById(int $id): ?Post
    {
        $sql = $this->getBaseSelectQuery() . " WHERE a.id = :id GROUP BY a.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Post($row) : null;
    }
    /**
     * Crée un nouvel article
     *
     * @param Post $post
     * @return bool
     */
    public function save(Post $post): bool
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, media_path, media_type, date_publication)
                VALUES (:titre, :contenu, :auteur_id, :media_path, :media_type, NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'titre'      => $post->titre,
            'contenu'    => $post->contenu,
            'auteur_id'  => $post->auteur_id,
            'media_path' => $post->media_path,
            'media_type' => $post->media_type
        ]);
    }

    /**
     * Met à jour un article existant
     *
     * @param Post $post
     * @return bool
     */
    public function update(Post $post): bool
    {
        $sql = "UPDATE articles 
                SET titre = :titre, contenu = :contenu, media_path = :media_path, media_type = :media_type
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'titre'      => $post->titre,
            'contenu'    => $post->contenu,
            'media_path' => $post->media_path,
            'media_type' => $post->media_type,
            'id'         => $post->id
        ]);
    }

    /**
     * Supprime un article
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
