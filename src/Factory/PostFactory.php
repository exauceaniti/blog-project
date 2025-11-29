<?php

namespace Src\Factory;

use Src\Entity\Post;

class PostFactory
{
    /**
     * Crée un objet Post à partir des données brutes
     *
     * @param array $data
     * @return Post
     */
    public static function create(array $data): Post
    {
        // L'entité Post gère maintenant l'hydratation et les valeurs par défaut
        return new Post($data);
    }
}
