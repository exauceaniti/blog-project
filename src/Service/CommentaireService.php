<?php
namespace Controllers\Services;

use Models\DAO\CommentaireDAO;
use Models\Entity\Commentaire;

class CommentaireService {
    private CommentaireDAO $commentaireDAO;

    public function __construct() {
        $this->commentaireDAO = new CommentaireDAO();
    }

    public function getCommentsForPost(int $postId): array {
        return $this->commentaireDAO->findByPost($postId);
    }

    public function addComment(array $data): bool {
        $comment = new Commentaire();
        $comment->content = $data['content'];
        $comment->user_id = $data['user_id'];
        $comment->post_id = $data['post_id'];
        return $this->commentaireDAO->save($comment);
    }
}
