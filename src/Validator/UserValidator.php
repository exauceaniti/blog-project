<?php

namespace App\Validator;

use App\Core\Lang\MessageBag;

/**
 * Validateur pour les données utilisateur
 * 
 * Cette classe contient les règles de validation pour les opérations
 * liées aux utilisateurs (inscription, connexion, mise à jour)
 */
class UserValidator
{
    /**
     * Valide les données d'inscription ou de mise à jour utilisateur
     * - Vérifie le nom (minimum 2 caractères)
     * - Valide le format de l'email
     * - Optionnellement vérifie le mot de passe (6 caractères minimum)
     *
     * @param array $data Données brutes (ex. $_POST)
     * @param bool $checkPassword Indique si le mot de passe doit être validé
     * @return array Liste des messages d'erreur
     */
    public static function validate(array $data, bool $checkPassword = true): array
    {
        $errors = [];

        // Nom
        if (empty($data['nom']) || strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = MessageBag::get('user.nom_required');
        }

        // Email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = MessageBag::get('user.email_invalid');
        }

        // Mot de passe doit être supérieur à 6 caractères
        if ($checkPassword) {
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $errors['password'] = MessageBag::get('user.password_short');
            }
        }

        return $errors;
    }

    /**
     * Valide uniquement les données de connexion
     * - Vérifie la présence et le format de l'email
     * - Vérifie la présence du mot de passe
     *
     * @param array $data Données de connexion (email et password)
     * @return array Liste des messages d'erreur
     */
    public static function validateLogin(array $data): array
    {
        $errors = [];

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = MessageBag::get('user.email_invalid');
        }

        if (empty($data['password'])) {
            $errors['password'] = MessageBag::get('user.password_short');
        }

        return $errors;
    }
}
