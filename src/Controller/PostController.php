<?php

namespace App\Controller;

use App\Service\PostService;
use App\Validator\PostValidator;
use App\Core\Session\FlashManager;
use App\Core\Http\Redirector;
use App\Core\Lang\MessageBag;
use App\Controller\BaseController;
use App\Core\Auth\Authentification;

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

    /**
     * Affiche le formulaire de modification pour un article (GET /admin/posts/edit/{id})
     */
    public function displayUpdateForm(int $id): void
    {
        // NOTE: Ici, il faudrait idéalement vérifier si l'utilisateur connecté 
        // est autorisé à modifier cet article (middleware ou vérification interne).

        $post = $this->postService->getPostById($id);

        if (!$post) {
            FlashManager::error('Article introuvable.');
            Redirector::to('/admin/posts');
            return;
        }

        // Rendu de la vue d'édition (Views/admin/modifier.php)
        $this->render('admin/modifier', [
            'title' => 'Modifier l\'article #' . $id,
            'article' => $post,
        ], 'layout/admin');
    }


    // --- 1. CREATE NEW POST---

    /**
     * Crée un nouvel article à partir des données POST.
     * Route de traitement: POST /post/create
     */
    public function create(): void
    {
        // 0. Vérification de sécurité (Si le middleware 'Auth' n'a pas déjà bloqué)
        if (!Authentification::isLoggedIn()) {
            FlashManager::error(MessageBag::get('auth.forbidden') ?? "Accès non autorisé. Veuillez vous connecter.");
            Redirector::to('/login');
            return;
        }

        // 1. Préparation des données
        $data = $_POST;

        // RECUPERATION DE L'ID DE L'AUTEUR VIA LA CLASSE AUTHENTIFICATION
        $data['auteur_id'] = Authentification::getUserId();

        // Double vérification si getUserId retourne null (impossible si isLoggedIn() est vrai, mais bonne pratique)
        if ($data['auteur_id'] === null) {
            FlashManager::error(MessageBag::get('system.unknown_user') ?? "Erreur: Auteur non identifié.");
            Redirector::back();
            return;
        }

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
            FlashManager::success(MessageBag::get('article.create_success') ?? "Article créé avec succès.");
            // Redirige vers la liste des articles admin ou la page de l'article créé
            Redirector::to('/admin/posts');
        } else {
            FlashManager::error(MessageBag::get('system.action_failed') ?? "Échec de la création de l'article.");
            Redirector::back();
        }
    }
    
    // --- 2. UPDATE ---

    /**
     * Met à jour un article existant.
     */
    public function update(int $id): void
    {
        // NOTE: Il est crucial ici d'ajouter une vérification d'autorisation (user_id du post == user_id connecté)
        // si l'article n'appartient pas à l'admin.

        $data = $_POST;

        // On récupère l'ID de l'utilisateur pour le passer au Service si nécessaire pour la vérification des droits
        $data['current_user_id'] = Authentification::getUserId();

        // Validation
        $errors = PostValidator::validate($data);

        if (!empty($errors)) {
            FlashManager::error(MessageBag::get('form.validation_errors') . '<br>' . implode('<br>', $errors));
            Redirector::back();
            return;
        }

        // 1. Appel au Service
        $success = $this->postService->updatePost($id, $data);

        if ($success) {
            FlashManager::success(MessageBag::get('article.update_success') ?? "Article mis à jour avec succès.");
            // Redirige vers la vue détaillée de l'article mis à jour
            Redirector::to("/articles/{$id}");
        } else {
            FlashManager::error(MessageBag::get('system.action_failed') ?? "Échec de la mise à jour de l'article.");
            Redirector::back();
        }
    }
    
    // --- 3. DELETE ---

    /**
     * Supprime un article par son ID.
     */
    public function delete(int $id): void
    {
        // NOTE: Même remarque: Vérification d'autorisation est nécessaire ici.

        $currentUserId = Authentification::getUserId();

        // 1. Appel au Service (le service peut effectuer la vérification des droits si vous ne le faites pas ici)
        $success = $this->postService->deletePost($id, $currentUserId);

        if ($success) {
            FlashManager::success(MessageBag::get('article.delete_success') ?? "Article supprimé avec succès.");
        } else {
            FlashManager::error(MessageBag::get('system.action_failed') ?? "Échec de la suppression de l'article.");
        }

        // 2. Redirige toujours vers la page d'administration des articles après la suppression
        Redirector::to('/admin/posts');
    }


}
