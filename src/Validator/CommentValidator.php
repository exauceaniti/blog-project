<?php
namespace Src\Validator;

use Src\Core\Lang\MessageBag;

class CommentValidator {
    /**
     * Valide les données d’un commentaire
     *
     * @param array $data
     * @return array Liste des erreurs (vide si OK)
     */
    public static function validate(array $data): array {
        $errors = [];

        // Vérification du contenu
        if (empty($data['contenu'])) {
            $errors[] = MessageBag::get('comment.content_required');
        } elseif (strlen($data['contenu']) < 3) {
            $errors[] = MessageBag::get('comment.content_too_short');
        } elseif (strlen($data['contenu']) > 500) {
            $errors[] = MessageBag::get('comment.content_too_long');
        }

        // Vérification auteur
        if (empty($data['auteur_id']) || !is_numeric($data['auteur_id'])) {
            $errors[] = MessageBag::get('user.account_required');
        }

        // Vérification article
        if (empty($data['article_id']) || !is_numeric($data['article_id'])) {
            $errors[] = MessageBag::get('article.not_found');
        }

        return $errors;
    }
}
