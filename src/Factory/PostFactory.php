<?php
namespace Src\Factory;

use Src\Entity\Post;

class PostFactory {
    /**
     * Crée un objet Post à partir des données brutes
     *
     * @param array $data
     * @return Post
     */
    public static function create(array $data): Post {
        return new Post([
            'id'              => $data['id'] ?? 0,
            'titre'           => $data['titre'] ?? '',
            'contenu'         => $data['contenu'] ?? '',
            'auteur_id'       => $data['auteur_id'] ?? 0,
            'date_publication'=> $data['date_publication'] ?? date('Y-m-d H:i:s'),
            'media_path'      => $data['media_path'] ?? null,
            'media_type'      => $data['media_type'] ?? null,
        ]);
    }
}
