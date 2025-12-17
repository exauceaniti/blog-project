<?php

namespace App\Controller;

use App\Core\Auth\Authentification;
use App\Service\CommentService;
use App\Validator\CommentValidator;
use App\Core\Session\FlashManager;
use App\Core\Http\Redirector;
use App\Core\Lang\MessageBag;
use App\Controller\BaseController;

class CommentController extends BaseController
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Affiche les commentaires d’un article
     */
    public function list(int $articleId): void
    {
        $comments = $this->commentService->getCommentsByArticle($articleId);
        $this->render('comment/list', ['comments' => $comments], 'layout/public');
    }

    /**
     * Ajoute un commentaire
     */
    public function add(): void
    {

        // 1. VÉRIFICATION DE L'AUTHENTIFICATION
        if (!Authentification::isLoggedIn()) {
            FlashManager::error(MessageBag::get('auth.login_required') ?? "Vous devez être connecté pour poster un commentaire.");
            // Redirige vers la page de l'article pour éviter de perdre le contexte
            Redirector::back();
            return;
        }

        $data = $_POST;

        // 2. RÉCUPÉRATION CORRECTE DE L'ID DE L'AUTEUR
        // Nous utilisons la méthode getUserId qui lit $_SESSION['user']['id']
        $data['auteur_id'] = Authentification::getUserId();

        // Si l'ID est null même après la vérification (sécurité supplémentaire, bien que non nécessaire ici)
        if ($data['auteur_id'] === null) {
            FlashManager::error(MessageBag::get('system.unknown_user') ?? "Erreur: Utilisateur non identifié.");
            Redirector::back();
            return;
        }

        // 3. Validation
        // Note: La validation doit aussi vérifier la présence de 'article_id' dans $_POST.
        $errors = CommentValidator::validate($data);
        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        // 4. Enregistrement
        $success = $this->commentService->addComment($data);

        if ($success) {
            FlashManager::success(MessageBag::get('comment.add_success') ?? "Commentaire ajouté avec succès !");
        } else {
            FlashManager::error(MessageBag::get('system.action_failed') ?? "Une erreur est survenue lors de l'ajout du commentaire.");
        }

        Redirector::back();
    }

    /**
     * Met à jour un commentaire
     */
    // ... Reste des méthodes (update et delete) inchangées ...

    public function update(int $id): void
    {
        // ... (code inchangé)
    }

    /**
     * Supprime un commentaire
     */
    public function delete(int $id): void
    {
        // ... (code inchangé)
    }
}
