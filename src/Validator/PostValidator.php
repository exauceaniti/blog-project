<?php
namespace Src\Validator;

use Src\Core\Lang\MessageBag;

class PostValidator {
    /**
     * Valide les données d’un article
     *
     * @param array $data Données brutes ($_POST + $_FILES)
     * @return array Liste des erreurs (vide si OK)
     */
    public static function validate(array $data): array {
        $errors = [];

        // Vérification du titre
        if (empty($data['titre']) || strlen($data['titre']) < 5) {
            $errors[] = MessageBag::get('article.title_required');
        }

        // Vérification du contenu
        if (empty($data['contenu']) || strlen($data['contenu']) < 20) {
            $errors[] = MessageBag::get('article.content_required');
        }

        // Vérification auteur
        if (empty($data['auteur_id']) || !is_numeric($data['auteur_id'])) {
            $errors[] = MessageBag::get('user.account_required');
        }

        // Vérification du média (si présent)
        if (!empty($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['media'];

            // Taille max : 5 Mo
            if ($file['size'] > 5 * 1024 * 1024) {
                $errors[] = MessageBag::get('form.file_too_large');
            }

            // Types autorisés
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4'];
            if (!in_array($file['type'], $allowedTypes)) {
                $errors[] = MessageBag::get('form.file_type_invalid');
            }
        }

        return $errors;
    }
}
