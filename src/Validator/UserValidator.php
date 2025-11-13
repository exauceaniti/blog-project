<?php
namespace Src\Validator;

use Src\Core\Lang\MessageBag;

class UserValidator {
    /**
     * Valide les données d'inscription ou de mise à jour utilisateur
     *
     * @param array $data Données brutes (ex. $_POST)
     * @param bool $checkPassword Indique si le mot de passe doit être validé
     * @return array Liste des messages d'erreur
     */
    public static function validate(array $data, bool $checkPassword = true): array {
        $errors = [];

        // Nom
        if (empty($data['nom']) || strlen(trim($data['nom'])) < 2) {
            $errors[] = MessageBag::get('user.nom_required');
        }

        // Email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = MessageBag::get('user.email_invalid');
        }

        // Mot de passe doit etre superieur a 6 caracteres
        if ($checkPassword) {
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $errors[] = MessageBag::get('user.password_short');
            }
        }

        return $errors;
    }

    /**
     * Valide uniquement les données de connexion
     *
     * @param array $data
     * @return array
     */
    public static function validateLogin(array $data): array {
        $errors = [];

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = MessageBag::get('user.email_invalid');
        }

        if (empty($data['password'])) {
            $errors[] = MessageBag::get('user.password_short');
        }

        return $errors;
    }
}
