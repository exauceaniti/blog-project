<?php
/**
 * Je mets ici toute la logique de validation à utiliser dans cette classe Validator.
 *
 * 1- isEmpty($value) → Vérifie si une valeur est vide ou non définie.
 * 2- isEmail($email) → Vérifie si une chaîne est un email valide.
 * 3- isNumeric($value) → Vérifie si une valeur est un nombre.
 * 4- hasMinLength($value, $min) → Vérifie si une chaîne atteint une longueur minimale.
 * 5- hasMaxLength($value, $max) → Vérifie si une chaîne ne dépasse pas une longueur maximale.
 * 6- isValidFileType($file, $allowedTypes) → Vérifie si le type MIME du fichier est autorisé.
 * 7- isValidFileSize($file, $maxSize) → Vérifie si la taille du fichier est inférieure à la limite.
 * 8- sanitizeFileName($name) → Nettoie le nom du fichier pour éviter les caractères dangereux.
 * 9- generateUniqueFileName($originalName) → Génère un nom unique pour éviter les conflits.
 * 10- validateArticleData($titre, $contenu, $auteurId, $media) → Regroupe toutes les validations nécessaires pour un article.
 */



class Validator
{
    public function isEmpty($value)
    {
        return empty(trim($value));
    }

    public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function isNumeric($value)
    {
        return is_numeric($value);
    }

    public function hasMinLength($value, $min)
    {
        return strlen(trim($value)) >= $min;
    }

    public function hasMaxLength($value, $max)
    {
        return strlen(trim($value)) <= $max;
    }

    public function isValidFileType($file, $allowedTypes)
    {
        return in_array($file['type'], $allowedTypes);
    }

    public function isValidFileSize($file, $maxSize)
    {
        return $file['size'] <= $maxSize;
    }

    public function sanitizeFileName($name)
    {
        return preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $name);
    }

    public function generateUniqueFileName($originalName)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid('media_', true) . '.' . $extension;
    }

    public function validateArticleData($titre, $contenu, $auteurId, $media = null)
    {
        $errors = [];

        if ($this->isEmpty($titre)) {
            $errors[] = "Le titre est requis.";
        } elseif (!$this->hasMinLength($titre, 5)) {
            $errors[] = "Le titre doit contenir au moins 5 caractères.";
        }

        if ($this->isEmpty($contenu)) {
            $errors[] = "Le contenu est requis.";
        } elseif (!$this->hasMinLength($contenu, 20)) {
            $errors[] = "Le contenu est trop court.";
        }

        if (!$this->isNumeric($auteurId)) {
            $errors[] = "L'identifiant de l'auteur est invalide.";
        }

        if ($media && $media['error'] === UPLOAD_ERR_OK) {
            if (!$this->isValidFileType($media, ['image/jpeg', 'image/png', 'video/mp4'])) {
                $errors[] = "Type de fichier non autorisé.";
            }

            if (!$this->isValidFileSize($media, 5 * 1024 * 1024)) {
                $errors[] = "Fichier trop volumineux (max 5 Mo).";
            }
        }

        return $errors;
    }
}
