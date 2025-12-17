<?php

namespace App\Service;

use App\DAO\CommentDAO;
use App\Entity\Comment;
use App\Factory\CommentFactory;

class CommentService
{
    private CommentDAO $commentDAO;

    public function __construct(CommentDAO $commentDAO)
    {
        $this->commentDAO = $commentDAO;
    }

    /**
     * Récupère tous les commentaires d’un article
     *
     * @param int $articleId
     * @return Comment[]
     */
    public function getCommentsByArticle(int $articleId): array
    {
        return $this->commentDAO->findByArticle($articleId);
    }

    /**
     * Récupère un commentaire par son ID
     *
     * @param int $id
     * @return ?Comment
     */
    public function getCommentById(int $id): ?Comment
    {
        return $this->commentDAO->findById($id);
    }

    /**
     * Ajoute un nouveau commentaire
     *
     * @param array $data Données brutes ($_POST)
     * @return bool
     */
    public function addComment(array $data): bool
    {
        // Ici on délègue la construction à la Factory
        $comment = CommentFactory::create($data);
        return $this->commentDAO->save($comment);
    }

    /**
     * Met à jour un commentaire existant
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateComment(int $id, array $data): bool
    {
        $comment = $this->commentDAO->findById($id);
        if (!$comment) {
            return false;
        }

        // Mise à jour des champs
        $comment->contenu = $data['contenu'] ?? $comment->contenu;

        return $this->commentDAO->update($comment);
    }

    /**
     * Supprime un commentaire
     *
     * @param int $id
     * @return bool
     */
    public function deleteComment(int $id): bool
    {
        return $this->commentDAO->delete($id);
    }


    /**     * Récupère le nombre de commentaires pour un article donné
     *
     * @param int $articleId
     * @return int
     */
    public function getCommentsCountByArticle(int $articleId): int
    {
        return count($this->commentDAO->findByArticle($articleId));
    }
}
