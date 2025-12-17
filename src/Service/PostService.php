<?php

namespace App\Service;

use App\DAO\PostDAO;
use App\Entity\Post;
use App\Service\MediaUploader;
use Dompdf\Dompdf;
use Dompdf\Options;

class PostService
{
    private PostDAO $postDAO;
    private MediaUploader $mediaUploader; // Dépendance de l'uploader

    /**
     * Le constructeur reçoit maintenant DEUX dépendances.
     */
    public function __construct(PostDAO $postDAO, MediaUploader $mediaUploader)
    {
        $this->postDAO = $postDAO;
        $this->mediaUploader = $mediaUploader;
    }

    // --- Méthodes de Récupération (LECTURE) ---

    /**
     * Récupère tous les articles (pour la page /articles)
     *
     * @return Post[]
     */
    public function getAllPosts(): array
    {
        return $this->postDAO->findAll();
    }

    /**
     * NOUVEAU : Récupère les 5 derniers articles (pour la page d'accueil)
     *
     * @return Post[]
     */
    public function getLatestPosts(int $limit = 5): array
    {
        // Appel de la méthode optimisée dans le DAO
        return $this->postDAO->findLatest($limit);
    }


    /**
     * Récupère un article par son ID
     *
     * @param int $id
     * @return ?Post
     */
    public function getPostById(int $id): ?Post
    {
        return $this->postDAO->findById($id);
    }

    // --- Méthodes de Mutation (ÉCRITURE) ---

    /**
     * Crée un nouvel article à partir des données validées.
     * Le service gère l'upload de média avant la persistance.
     *
     * @param array $data Données brutes (validées, incluant $_POST et potentiellement $_SESSION)
     * @return bool
     */
    public function createPost(array $data): bool
    {
        // 1. Gestion du Média via MediaUploader
        $uploadInfo = $this->mediaUploader->handleUpload($_FILES['media'] ?? null);

        // 2. Préparation des données pour l'Entité
        $data['media_path'] = $uploadInfo['path'] ?? null;
        $data['media_type'] = $uploadInfo['type'] ?? null;

        // 3. Hydratation de l'Entité (utilisant le constructeur de Post)
        $post = new Post($data);

        // 4. Sauvegarde dans la DB
        return $this->postDAO->save($post);
    }

    /**
     * Met à jour un article existant
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updatePost(int $id, array $data): bool
    {
        $post = $this->postDAO->findById($id);
        if (!$post) {
            return false;
        }

        // 1. Gestion de l'Upload (Si un nouveau fichier est fourni)
        $uploadInfo = $this->mediaUploader->handleUpload($_FILES['media'] ?? null);

        if ($uploadInfo) {
            // Si un nouveau média est uploadé, supprimer l'ancien fichier
            $this->mediaUploader->delete($post->media_path);

            $post->media_path = $uploadInfo['path'];
            $post->media_type = $uploadInfo['type'];
        }

        // 2. Mise à jour des champs texte (utilisation de l'opérateur de coalescence ?? pour garder l'ancienne valeur si la nouvelle est absente)
        $post->titre      = $data['titre'] ?? $post->titre;
        $post->contenu    = $data['contenu'] ?? $post->contenu;

        // 3. Sauvegarde dans la DB
        return $this->postDAO->update($post);
    }

    /**
     * Supprime un article
     *
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id): bool
    {
        $post = $this->postDAO->findById($id);
        if (!$post) {
            return false;
        }

        // 1. Supprimer le fichier média associé du serveur
        $this->mediaUploader->delete($post->media_path);

        // 2. Supprimer l'enregistrement de la base de données
        // Le CASCADE DELETE de la DB s'occupera des commentaires
        return $this->postDAO->delete($id);
    }

public function generatePdfFromPosts(array $posts): string
{
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'Helvetica');
    $dompdf = new \Dompdf\Dompdf($options);

    // On définit la racine du projet de manière hyper précise
    $projectRoot = realpath(__DIR__ . '/../../'); 
    $publicFolder = $projectRoot . '/public/uploads';

    foreach ($posts as $post) {
        // On nettoie le chemin : on enlève le slash au début s'il y en a un
        $cleanPath = ltrim($post->media_path, '/');
        $fullPath = $publicFolder . '/' . $cleanPath;

        if (!empty($post->media_path) && file_exists($fullPath)) {
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $data = file_get_contents($fullPath);
            $post->image_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            // Si l'image n'est pas trouvée, on met une image par défaut ou rien
            $post->image_base64 = null;
            // Décommenter la ligne suivante pour DEBUGGER si besoin :
            // die("Le fichier n'existe pas ici : " . $fullPath);
        }
    }

    ob_start();
    require __DIR__ . '/../../templates/pdf/post_pdf_template.php';
    $html = ob_get_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return $dompdf->output();
}
}
