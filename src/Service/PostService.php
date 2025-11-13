<?php
namespace Controllers\Services;

use Models\DAO\PostDAO;
use Models\Entity\Post;

class PostService {
    private PostDAO $postDAO;

    public function __construct() {
        $this->postDAO = new PostDAO();
    }

    public function getAllPosts(): array {
        return $this->postDAO->findAll();
    }

    public function getPost(int $id): ?Post {
        return $this->postDAO->findById($id);
    }

    public function createPost(array $data): bool {
        $post = new Post();
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->user_id = $data['user_id'];
        return $this->postDAO->save($post);
    }
}
