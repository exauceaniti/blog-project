<?php

namespace Src\Controller;

use Src\Service\PostService;
use Src\Validator\PostValidator;
use Src\Core\Session\FlashManager;
use Src\Core\Http\Redirector;
use Src\Core\Lang\MessageBag;
use Src\Controller\BaseController;

/**
 * PostController
 * Gère uniquement les actions de mutation (CREATE, UPDATE, DELETE) pour les articles.
 */
class PostController extends BaseController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }


    // --- 1. CREATE NEW POST---

    /**
     * Crée un nouvel article à partir des données POST.
     * Route de traitement: POST /post/create
     */
    public function create(): void
    {
        // 1. Préparation des données
        $data = $_POST;
        // L'auteur est l'utilisateur connecté (middleware 'auth' doit garantir la session)
        // $data['auteur_id'] = $_SESSION['user_id'] ?? null;

        // 2. Validation
        $errors = PostValidator::validate($data);

        if (!empty($errors)) {
            FlashManager::error(MessageBag::get('form.validation_errors') . '<br>' . implode('<br>', $errors));
            Redirector::back();
            return;
        }

        // 3. Appel au Service (gestion de l'upload et de la DB)
        $success = $this->postService->createPost($data);

        if ($success) {
            FlashManager::success(MessageBag::get('article.create_success'));
            // Redirector::to(''); 
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
            Redirector::back();
        }
    }
    
    // --- 2. UPDATE ---

    /**
     * Met à jour un article existant.
     * Route de traitement: POST /post/update/{id}
     *
     * @param int $id ID de l'article à mettre à jour.
     */
    public function update(int $id): void
    {
        $data = $_POST;

        // Note: La validation doit être adaptée en mode UPDATE. 
        // Par exemple, si le média n'est pas remplacé, $_FILES est vide, 
        // mais les autres champs sont vérifiés.
        $errors = PostValidator::validate($data);

        if (!empty($errors)) {
            FlashManager::error(MessageBag::get('form.validation_errors') . '<br>' . implode('<br>', $errors));
            Redirector::back();
            return;
        }

        // 1. Appel au Service
        // Le service gère la recherche par ID, l'upload de remplacement, et la mise à jour des champs.
        $success = $this->postService->updatePost($id, $data);

        if ($success) {
            FlashManager::success(MessageBag::get('article.update_success'));
            // Redirige vers la vue détaillée de l'article mis à jour
            Redirector::to("/articles/{$id}");
        } else {
            // L'échec peut signifier ID non trouvé ou échec DB/Média
            FlashManager::error(MessageBag::get('system.action_failed'));
            Redirector::back();
        }
    }
    
    // --- 3. DELETE ---

    /**
     * Supprime un article par son ID.
     * Route de traitement: POST /post/delete/{id}
     *
     * @param int $id ID de l'article à supprimer.
     */
    public function delete(int $id): void
    {
        // 1. Appel au Service
        // Le service gère la suppression du fichier média, puis l'enregistrement DB.
        $success = $this->postService->deletePost($id);

        if ($success) {
            FlashManager::success(MessageBag::get('article.delete_success'));
        } else {
            FlashManager::error(MessageBag::get('system.action_failed'));
        }

        // 2. Redirige toujours vers la page d'administration des articles après la suppression
        Redirector::to('/url');
    }
}
