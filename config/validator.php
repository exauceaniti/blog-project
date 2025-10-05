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
    /**
     * Nettoie une donnée en supprimant les espaces et balises HTML dangereuses.
     */
    public function sanitize($value)
    {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Vérifie si une valeur est vide (après nettoyage).
     */
    public function isEmpty($value)
    {
        return empty(trim($value));
    }

    /**
     * Vérifie la longueur minimale d'une chaîne.
     */
    public function hasMinLength($value, $min)
    {
        return strlen(trim($value)) >= $min;
    }

    /**
     * Vérifie la longueur maximale d'une chaîne.
     */
    public function hasMaxLength($value, $max)
    {
        return strlen(trim($value)) <= $max;
    }

    /**
     * Vérifie si une valeur est numérique.
     */
    public function isNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Vérifie si l'email est valide.
     */
    public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Vérifie le type MIME du fichier.
     */
    public function isValidFileType($file, $allowedTypes)
    {
        return in_array($file['type'], $allowedTypes);
    }

    /**
     * Vérifie la taille du fichier (en octets).
     */
    public function isValidFileSize($file, $maxSize)
    {
        return isset($file['size']) && $file['size'] <= $maxSize;
    }

    /**
     * Nettoie le nom du fichier pour éviter les caractères spéciaux dangereux.
     */
    public function sanitizeFileName($name)
    {
        $cleanName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $name);
        return $this->sanitize($cleanName);
    }

    /**
     * Génère un nom de fichier unique pour éviter les collisions.
     */
    public function generateUniqueFileName($originalName)
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        return uniqid('media_', true) . '.' . $extension;
    }

    /**
     * Vérifie la validité de l’extension du fichier par rapport à son type MIME.
     */
    public function hasValidExtension($file)
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime = $file['type'];

        $allowed = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'mp4' => 'video/mp4'
        ];

        return isset($allowed[$extension]) && $allowed[$extension] === $mime;
    }

    /**
     * Validation complète des données d’un article
     */
    public function validateArticleData($titre, $contenu, $auteurId, $media = null)
    {
        $errors = [];

        // Nettoyage des entrées
        $titre = $this->sanitize($titre);
        $contenu = $this->sanitize($contenu);

        // Titre
        if ($this->isEmpty($titre)) {
            $errors[] = "Le titre est requis.";
        } elseif (!$this->hasMinLength($titre, 5)) {
            $errors[] = "Le titre doit contenir au moins 5 caractères.";
        }

        // Contenu
        if ($this->isEmpty($contenu)) {
            $errors[] = "Le contenu est requis.";
        } elseif (!$this->hasMinLength($contenu, 20)) {
            $errors[] = "Le contenu est trop court (min 20 caractères).";
        }

        // Auteur
        if (!$this->isNumeric($auteurId)) {
            $errors[] = "L'identifiant de l'auteur est invalide.";
        }

        // Fichier média
        if ($media && isset($media['error']) && $media['error'] === UPLOAD_ERR_OK) {
            if (!$this->hasValidExtension($media)) {
                $errors[] = "Le type de fichier n'est pas valide.";
            }

            if (!$this->isValidFileSize($media, 10 * 1024 * 1024)) { // 10 Mo
                $errors[] = "Le fichier est trop volumineux (max 10 Mo).";
            }
        }

        return $errors;
    }
}
