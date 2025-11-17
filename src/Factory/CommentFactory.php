<?php
namespace Src\Factory;

use Src\Entity\Comment;

class CommentFactory {
    /**
     * Crée un objet Comment à partir des données brutes
     *
     * @param array $data
     * @return Comment
     */
    public static function create(array $data): Comment {
        return new Comment([
            'id'               => $data['id'] ?? 0,
            'contenu'          => $data['contenu'] ?? '',
            'auteur_id'        => $data['auteur_id'] ?? 0,
            'article_id'       => $data['article_id'] ?? 0,
            'date_commentaire' => $data['date_commentaire'] ?? date('Y-m-d H:i:s'),
        ]);
    }
}
