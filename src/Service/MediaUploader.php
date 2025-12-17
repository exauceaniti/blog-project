<?php

namespace App\Service;

use Exception;

/**
 * MediaUploader
 * Gère l'upload, le renommage, le déplacement et la suppression des fichiers médias
 * sur le système de fichiers local.
 */
class MediaUploader
{
    // Le chemin où les fichiers seront stockés
    private string $uploadDir = __DIR__ . '/../../public/uploads';

    /**
     * Crée le dossier d'upload s'il n'existe pas lors de l'initialisation.
     */
    public function __construct()
    {
        if (!is_dir($this->uploadDir)) {
            // Créer le répertoire avec les permissions 0777 (à adapter selon votre environnement)
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Gère l'upload d'un fichier média et retourne ses informations de stockage.
     *
     * @param array|null $fileData Tableau $_FILES['media'] ou null
     * @return array|null Tableau ['path' => 'nom_du_fichier.ext', 'type' => 'image'] ou null
     */
    public function handleUpload(?array $fileData): ?array
    {
        // 1. Vérification initiale (fichier présent et upload sans erreur)
        if (empty($fileData) || $fileData['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // 2. Déterminer le type (image ou vidéo)
        $mime = $fileData['type'];
        $type = (strpos($mime, 'image/') === 0) ? 'image' : ((strpos($mime, 'video/') === 0) ? 'video' : null);

        if (!$type) {
            // Ici, vous pourriez lancer une exception ou loguer une erreur si vous voulez être plus strict
            return null;
        }

        // 3. Renommer le fichier (Sécurité et Unicité)
        $extension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('media_', true) . '.' . strtolower($extension);
        $destination = $this->uploadDir . '/' . $fileName;

        // 4. Déplacer le fichier du répertoire temporaire vers le répertoire permanent
        if (move_uploaded_file($fileData['tmp_name'], $destination)) {
            return [
                'path' => $fileName, // C'est cette partie qui va dans la DB
                'type' => $type
            ];
        }

        // Échec du déplacement
        return null;
    }

    /**
     * Supprime un fichier média du système de fichiers.
     *
     * @param string|null $fileName Le nom du fichier à supprimer (ex: media_65b3d7.jpg)
     * @return bool Vrai si le fichier est supprimé ou n'existe pas, Faux en cas d'erreur de suppression.
     */
    public function delete(?string $fileName): bool
    {
        if (empty($fileName)) {
            return true; // Rien à supprimer
        }

        $filePath = $this->uploadDir . '/' . $fileName;

        // Vérifier si le fichier existe avant de tenter la suppression
        if (file_exists($filePath)) {
            // Tentative de suppression
            if (unlink($filePath)) {
                return true;
            } else {
                // Échec de la suppression (problème de permission)
                // En production, vous loggerez cette erreur
                error_log("Impossible de supprimer le fichier: " . $filePath);
                return false;
            }
        }

        return true; // Le fichier n'existait pas
    }
}
