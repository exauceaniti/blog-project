<?php

namespace App\DAO;

use App\Entity\Comment;
use PDO;

/**
 * CommentDAO
 * ----------
 * Gère les opérations CRUD sur la table `commentaires`.
 */
class CommentDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les commentaires d’un article
     *
     * @param int $articleId
     * @return Comment[]
     */
    public function findByArticle(int $articleId): array
    {
        $sql = "SELECT * FROM commentaires WHERE article_id = :article_id ORDER BY date_commentaire DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['article_id' => $articleId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Comment($row), $rows);
    }

    /**
     * Récupère un commentaire par son ID
     *
     * @param int $id
     * @return ?Comment
     */
    public function findById(int $id): ?Comment
    {
        $sql = "SELECT * FROM commentaires WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Comment($row) : null;
    }

    /**
     * Ajoute un nouveau commentaire
     *
     * @param Comment $comment
     * @return bool
     */
    public function save(Comment $comment): bool
    {
        $sql = "INSERT INTO commentaires (contenu, auteur_id, article_id, date_commentaire)
                VALUES (:contenu, :auteur_id, :article_id, NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'contenu'    => $comment->contenu,
            'auteur_id'  => $comment->auteur_id,
            'article_id' => $comment->article_id
        ]);
    }

    /**
     * Met à jour un commentaire existant
     *
     * @param Comment $comment
     * @return bool
     */
    public function update(Comment $comment): bool
    {
        $sql = "UPDATE commentaires 
                SET contenu = :contenu 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'contenu' => $comment->contenu,
            'id'      => $comment->id
        ]);
    }

    /**
     * Supprime un commentaire
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM commentaires WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
