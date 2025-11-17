<?php
namespace Src\Service;

use Src\DAO\PostDAO;
use Src\Entity\Post;
use Src\Factory\PostFactory;

class PostService {
    private PostDAO $postDAO;

    public function __construct(PostDAO $postDAO) {
        $this->postDAO = $postDAO;
    }

    /**
     * Récupère tous les articles
     *
     * @return Post[]
     */
    public function getAllPosts(): array {
        return $this->postDAO->findAll();
    }

    /**
     * Récupère un article par son ID
     *
     * @param int $id
     * @return ?Post
     */
    public function getPostById(int $id): ?Post {
        return $this->postDAO->findById($id);
    }

    /**
     * Crée un nouvel article
     *
     * @param array $data Données brutes (ex: $_POST)
     * @return bool
     */
    public function createPost(array $data): bool {
        // Validation minimale côté service
        if (empty($data['titre']) || empty($data['contenu']) || empty($data['auteur_id'])) {
            return false;
        }

        // Construction via Factory
        $post = PostFactory::create($data);

        return $this->postDAO->save($post);
    }

    /**
     * Met à jour un article existant
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePost(int $id, array $data): bool {
        $post = $this->postDAO->findById($id);
        if (!$post) {
            return false;
        }

        // Mise à jour des champs
        $post->titre      = $data['titre'] ?? $post->titre;
        $post->contenu    = $data['contenu'] ?? $post->contenu;
        $post->media_path = $data['media_path'] ?? $post->media_path;
        $post->media_type = $data['media_type'] ?? $post->media_type;

        return $this->postDAO->update($post);
    }

    /**
     * Supprime un article
     *
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id): bool {
        return $this->postDAO->delete($id);
    }
}
