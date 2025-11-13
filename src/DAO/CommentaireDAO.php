<?php
namespace Models\DAO;

use Core\Database\Database;
use Models\Entity\Commentaire;
use PDO;

class CommentaireDAO {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByPost(int $postId): array {
        $stmt = $this->db->prepare("SELECT * FROM commentaires WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Commentaire::class);
    }

    public function save(Commentaire $comment): bool {
        $stmt = $this->db->prepare("INSERT INTO commentaires (content, user_id, post_id) VALUES (?, ?, ?)");
        return $stmt->execute([$comment->content, $comment->user_id, $comment->post_id]);
    }
}
