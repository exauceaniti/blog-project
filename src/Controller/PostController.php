<?php
namespace Src\Controller;

use Src\Service\PostService;
use Src\Validator\PostValidator;
use Src\Factory\PostFactory;
use Src\Core\Session\FlashManager;
use Src\Core\Http\Redirector;
use Src\Core\Lang\MessageBag;
use Src\Controller\BaseController;

class PostController extends BaseController {
    private PostService $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    /**
     * Liste des articles
     */
    public function index(): void {
        $posts = $this->postService->getAllPosts();
        $this->render('home/articles', ['articles_list' => $posts], 'layout/public');
    }

    /**
     * Affiche un article
     */
    public function show(int $id): void {
        $post = $this->postService->getPostById($id);
        if (!$post) {
            FlashManager::error(MessageBag::get('article.not_found'));
            $this->redirect('/articles');
        }
        $this->render('home/article_detail', ['article' => $post], 'layout/public');
    }

    /**
     * Crée un nouvel article
     */
    public function create(): void {
        $data = $_POST;
        $data['auteur_id'] = $_SESSION['user_id'] ?? null;

        // Validation
        $errors = PostValidator::validate($data);
        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        // Construction via Factory
        $post = PostFactory::create($data);

        $success = $this->postService->createPost($data);

        if ($success) {
            FlashManager::success(MessageBag::get('article.create_success'));
            Redirector::to('/articles');
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
            Redirector::back();
        }
    }

    /**
     * Met à jour un article
     */
    public function update(int $id): void {
        $data = $_POST;

        $errors = PostValidator::validate($data);
        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        $success = $this->postService->updatePost($id, $data);

        if ($success) {
            FlashManager::success(MessageBag::get('article.update_success'));
            Redirector::to("/articles/$id");
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
            Redirector::back();
        }
    }

    /**
     * Supprime un article
     */
    public function delete(int $id): void {
        $success = $this->postService->deletePost($id);

        if ($success) {
            FlashManager::success(MessageBag::get('article.delete_success'));
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
        }

        Redirector::to('/articles');
    }
}
