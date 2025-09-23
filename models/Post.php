<?php

/**
 * Classe Post - Gestion complète des articles du blog
 *
 * Cette classe permet de gérer le CRUD (Create, Read, Update, Delete) des articles
 * avec support de l'upload de médias (images et vidéos)
 *
 * @package Models
 */
class Post
{
    /**
     * @var object $conn Connexion à la base de données
     */
    private $conn;

    /**
     * Constructeur - Initialise la connexion à la base de données
     *
     * @param object $connexion Instance de la classe de connexion PDO
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    // MÉTHODES PRIVÉES (UTILITAIRES INTERNES)

    /**
     * Gère l'upload sécurisé d'un fichier média (image ou vidéo)
     *
     * @param array|null $fichier Tableau $_FILES['media'] ou null si aucun fichier
     * @return string|null Chemin relatif du fichier uploadé ou null en cas d'erreur
     *
     * @throws Exception Si le dossier upload ne peut pas être créé
     */
    private function uploadMedia($fichier)
    {
        // Vérification de l'existence et de l'absence d'erreurs du fichier
        if (!$fichier || $fichier['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Définition du chemin absolu du dossier d'upload
        $uploadDir = __DIR__ . "/../assets/uploads/";

        // Création du dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception("Impossible de créer le dossier d'upload");
            }
        }

        // Vérification des extensions autorisées
        $extensionsAutorisees = ["jpg", "jpeg", "png", "gif", "webp", "mp4", "avi", "mov"];
        $fileExt = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        // Validation de l'extension du fichier
        if (!in_array($fileExt, $extensionsAutorisees)) {
            return null; // Type de fichier non autorisé
        }

        // Génération d'un nom de fichier unique et sécurisé
        $fileName = time() . "_" . bin2hex(random_bytes(5)) . "." . $fileExt;
        $filePath = $uploadDir . $fileName;

        // Déplacement du fichier temporaire vers le dossier d'upload
        if (move_uploaded_file($fichier['tmp_name'], $filePath)) {
            // Retourne le chemin relatif pour l'affichage dans les vues HTML
            return "assets/uploads/" . $fileName;
        }

        return null; // Échec de l'upload
    }

    // MÉTHODES PUBLIQUES (CRUD - INTERFACE PRINCIPALE)

    /**
     * Ajoute un nouvel article dans la base de données
     *
     * @param string $titre Titre de l'article (255 caractères max)
     * @param string $contenu Contenu textuel de l'article (texte long)
     * @param int $auteurId ID de l'utilisateur auteur de l'article
     * @param array|null $fichier Fichier média optionnel à uploader
     *
     * @return object|false Résultat de la requête SQL ou false en cas d'erreur
     */
    public function ajouterArticle($titre, $contenu, $auteurId, $fichier = null)
    {
        // Upload du média si fourni
        $mediaPath = $this->uploadMedia($fichier);

        // Préparation de la requête SQL avec des paramètres sécurisés
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, media_path, media_type, date_publication)
                VALUES (?, ?, ?, ?, ?, NOW())";

        // Détermination du type de média
        $mediaType = $mediaPath ? $this->determinerTypeMedia($mediaPath) : 'none';

        // Exécution de la requête avec les paramètres
        return $this->conn->executerRequete($sql, [
            $titre,
            $contenu,
            $auteurId,
            $mediaPath,
            $mediaType
        ]);
    }

    /**
     * Modifie un article existant dans la base de données
     *
     * @param int $id ID de l'article à modifier
     * @param string $titre Nouveau titre de l'article
     * @param string $contenu Nouveau contenu de l'article
     * @param array|null $fichier Nouveau fichier média optionnel
     *
     * @return object|false Résultat de la requête SQL ou false en cas d'erreur
     */
    public function modifierArticle($id, $titre, $contenu, $fichier = null)
    {
        // Initialisation des paramètres de base
        $params = [$titre, $contenu];
        $sql = "UPDATE articles SET titre = ?, contenu = ?";

        // Gestion du nouveau média si fourni
        $mediaPath = $this->uploadMedia($fichier);
        if ($mediaPath) {
            $mediaType = $this->determinerTypeMedia($mediaPath);
            $sql .= ", media_path = ?, media_type = ?";
            $params[] = $mediaPath;
            $params[] = $mediaType;

            // Suppression de l'ancien média s'il existe
            $this->supprimerAncienMedia($id);
        }

        // Ajout de la clause WHERE et de l'ID
        $sql .= " WHERE id = ?";
        $params[] = $id;

        // Exécution de la requête
        return $this->conn->executerRequete($sql, $params);
    }

    /**
     * Supprime définitivement un article et son média associé
     *
     * @param int $id ID de l'article à supprimer
     *
     * @return object|false Résultat de la requête SQL ou false en cas d'erreur
     */
    public function supprimerArticle($id)
    {
        // Récupération des informations de l'article avant suppression
        $article = $this->getArticleById($id);

        if ($article) {
            // Suppression du fichier média physique s'il existe
            $this->supprimerFichierMedia($article['media_path']);
        }

        // Suppression de l'article dans la base de données
        $sql = "DELETE FROM articles WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }

    /**
     * Récupère tous les articles avec les informations de l'auteur
     *
     * @return array Tableau associatif de tous les articles ou tableau vide
     */
    public function voirArticles()
    {
        $sql = "SELECT a.*, u.nom AS auteur
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                ORDER BY a.date_publication DESC";

        $resultat = $this->conn->executerRequete($sql);
        return $resultat->fetchAll() ?: [];
    }

    /**
     * Recherche des articles par mot-clé dans le titre ou le contenu
     *
     * @param string $motCle Mot-clé à rechercher
     *
     * @return array Tableau des articles correspondants ou tableau vide
     */
    public function rechercherArticle($motCle)
    {
        $sql = "SELECT a.*, u.nom AS auteur
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                WHERE a.titre LIKE ? OR a.contenu LIKE ?
                ORDER BY a.date_publication DESC";

        $stmt = $this->conn->executerRequete($sql, ["%$motCle%", "%$motCle%"]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Récupère un article spécifique par son ID
     *
     * @param int $id ID de l'article à récupérer
     *
     * @return array|null Tableau associatif de l'article ou null si non trouvé
     */
    public function getArticleById($id)
    {
        $sql = "SELECT a.*, u.nom AS auteur
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                WHERE a.id = ?";

        $resultat = $this->conn->executerRequete($sql, [$id]);
        return $resultat->fetch() ?: null;
    }

    // MÉTHODES PRIVÉES ADDITIONNELLES (SUPPORT)

    /**
     * Détermine le type de média basé sur l'extension du fichier
     *
     * @param string $mediaPath Chemin du fichier média
     *
     * @return string Type de média ('image', 'video', 'audio', 'none')
     */
    private function determinerTypeMedia($mediaPath)
    {
        $extension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));

        $images = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $videos = ['mp4', 'avi', 'mov', 'mkv'];
        $audios = ['mp3', 'wav', 'ogg'];

        if (in_array($extension, $images)) return 'image';
        if (in_array($extension, $videos)) return 'video';
        if (in_array($extension, $audios)) return 'audio';

        return 'none';
    }

    /**
     * Supprime l'ancien fichier média lors d'une mise à jour
     *
     * @param int $articleId ID de l'article
     */
    private function supprimerAncienMedia($articleId)
    {
        $article = $this->getArticleById($articleId);
        if ($article && !empty($article['media_path'])) {
            $this->supprimerFichierMedia($article['media_path']);
        }
    }

    /**
     * Supprime physiquement un fichier média du serveur
     *
     * @param string $mediaPath Chemin relatif du fichier à supprimer
     */
    private function supprimerFichierMedia($mediaPath)
    {
        if (!empty($mediaPath)) {
            $filePath = __DIR__ . "/../" . $mediaPath;
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath); // Suppression du fichier physique
            }
        }
    }

    /**
     * Récupère les articles par auteur
     *
     * @param int $auteurId ID de l'auteur
     *
     * @return array Articles de l'auteur spécifié
     */
    public function getArticlesByAuteur($auteurId)
    {
        $sql = "SELECT a.*, u.nom AS auteur
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                WHERE a.auteur_id = ?
                ORDER BY a.date_publication DESC";

        $resultat = $this->conn->executerRequete($sql, [$auteurId]);
        return $resultat->fetchAll() ?: [];
    }

    /**
     * Compte le nombre total d'articles
     *
     * @return int Nombre total d'articles
     */
    public function compterArticles()
    {
        $sql = "SELECT COUNT(*) as total FROM articles";
        $resultat = $this->conn->executerRequete($sql);
        return $resultat->fetch()['total'] ?? 0;
    }
}
