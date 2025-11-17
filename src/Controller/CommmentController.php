<?php
namespace Src\Controller;

use Src\Service\CommentService;
use Src\Validator\CommentValidator;
use Src\Core\Session\FlashManager;
use Src\Core\Http\Redirector;
use Src\Core\Lang\MessageBag;
use Src\Controller\BaseController;

class CommentController extends BaseController {
    private CommentService $commentService;

    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

    /**
     * Affiche les commentaires d’un article
     */
    public function list(int $articleId): void {
        $comments = $this->commentService->getCommentsByArticle($articleId);
        $this->render('comment/list', ['comments' => $comments], 'layout/public');
    }

    /**
     * Ajoute un commentaire
     */
    public function add(): void {
        $data = $_POST;
        $data['auteur_id'] = $_SESSION['user_id'] ?? null;

        // Validation
        $errors = CommentValidator::validate($data);
        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        $success = $this->commentService->addComment($data);

        if ($success) {
            FlashManager::success(MessageBag::get('comment.add_success'));
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
        }

        Redirector::back();
    }

    /**
     * Met à jour un commentaire
     */
    public function update(int $id): void {
        $data = $_POST;

        $errors = CommentValidator::validate($data);
        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        $success = $this->commentService->updateComment($id, $data);

        if ($success) {
            FlashManager::success(MessageBag::get('comment.update_success'));
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
        }

        Redirector::back();
    }

    /**
     * Supprime un commentaire
     */
    public function delete(int $id): void {
        $success = $this->commentService->deleteComment($id);

        if ($success) {
            FlashManager::success(MessageBag::get('comment.delete_success'));
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
        }

        Redirector::back();
    }
}
