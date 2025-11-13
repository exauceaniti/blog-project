<?php
namespace Models\DAO;

use Core\Database\Database;
use Models\Entity\Post;
use PDO;

class PostDAO {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Post::class);
    }

    public function findById(int $id): ?Post {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Post::class);
        return $stmt->fetch() ?: null;
    }

    public function save(Post $post): bool {
        $stmt = $this->db->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        return $stmt->execute([$post->title, $post->content, $post->user_id]);
    }
}
