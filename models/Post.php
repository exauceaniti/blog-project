<?php

/**
 * @file Post.php
 * @description Modèle de gestion complète des articles blog avec système de médias
 * @author [Exauce Aniti]
 * @version 1.0
 * @date 2024
 *
 * @package Models
 * @class Post
 *
 * @feature CRUD complet articles + upload médias + recherche + statistiques
 * @security Gestion sécurisée des fichiers et validation des données
 */

/**
 * CLASSE POST - MODÈLE MÉTIER DES ARTICLES
 * @class Post
 * @description Gère toutes les opérations CRUD sur les articles avec support multimédia
 *
 * @property object $conn - Instance de connexion à la base de données
 * @method ajouterArticle() - Création d'un nouvel article avec média
 * @method modifierArticle() - Mise à jour d'un article existant
 * @method supprimerArticle() - Suppression définitive d'un article
 * @method voirArticles() - Récupération de tous les articles
 * @method rechercherArticle() - Recherche full-text dans les articles
 * @method getArticleById() - Récupération d'un article spécifique
 * @method getArticlesByAuteur() - Filtrage par auteur
 * @method compterArticles() - Statistiques des articles
 */
class Post
{
    /**
     * Instance de connexion à la base de données
     * @var object $conn
     * @access private
     * @description Objet PDO ou wrapper pour l'exécution des requêtes
     */
    private $conn;

    // ========================= CONSTRUCTEUR =========================
    /**
     * Constructeur de la classe Post
     * @constructor
     * @param object $connexion - Instance injectée de connexion BDD
     *
     * @dependency Injection de dépendance pour une meilleure testabilité
     * @principle Dependency Inversion Principle (SOLID)
     *
     * @example $post = new Post($connexion);
     *
     * @action Initialise la propriété $conn avec l'objet connexion
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    // ========================= MÉTHODES PRIVÉES (UTILITAIRES INTERNES) =========================

    /**
     * Gère l'upload sécurisé d'un fichier média (image/vidéo/audio)
     * @method uploadMedia
     * @access private
     * @param array|null $fichier - Tableau $_FILES['media'] ou null
     *
     * @process
     * 1. Validation du fichier et vérification d'erreurs
     * 2. Création du dossier upload si nécessaire
     * 3. Vérification des extensions autorisées
     * 4. Génération d'un nom de fichier unique et sécurisé
     * 5. Déplacement du fichier temporaire
     *
     * @security
     * - Vérification des types MIME via extensions
     * - Nom de fichier unique pour éviter les collisions
     * - Gestion des erreurs d'upload
     *
     * @param array $fichier Structure attendue:
     * [
     *     'name' => 'monfichier.jpg',
     *     'type' => 'image/jpeg',
     *     'tmp_name' => '/tmp/php1234.tmp',
     *     'error' => 0,
     *     'size' => 123456
     * ]
     *
     * @return string|null Chemin relatif du fichier uploadé ou null si erreur
     * @throws Exception Si impossibilité de créer le dossier d'upload
     *
     * @example $chemin = $this->uploadMedia($_FILES['media']);
     */
    private function uploadMedia($fichier)
    {
        if (!$fichier || $fichier['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Sécurité : vérifier que c'est un upload HTTP valide
        if (!is_uploaded_file($fichier['tmp_name'])) {
            return null;
        }

        // Chemin du dossier d'uploads (physique)
        $uploadDir = __DIR__ . "/../assets/uploads/";

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0775, true)) {
                throw new Exception("Impossible de créer le dossier d'upload");
            }
        }

        // Extension + détection MIME
        $fileExt = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        // extensions autorisées (images + vidéos)
        $allowedExt = ["jpg", "jpeg", "png", "gif", "webp", "mp4", "avi", "mov", "webm", "mkv"];
        if (!in_array($fileExt, $allowedExt)) {
            return null;
        }

        // Vérifier le MIME réel (finfo)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fichier['tmp_name']);
        finfo_close($finfo);

        // accepter images et video mime
        if (strpos($mime, 'image/') !== 0 && strpos($mime, 'video/') !== 0 && strpos($mime, 'audio/') !== 0) {
            return null;
        }

        // nom sécurisé + unique
        $fileName = time() . "_" . bin2hex(random_bytes(5)) . "." . $fileExt;
        $dest = $uploadDir . $fileName;

        if (!move_uploaded_file($fichier['tmp_name'], $dest)) {
            error_log("uploadMedia: move_uploaded_file failed for " . $fichier['name']);
            return null;
        }

        // permissions correctes
        @chmod($dest, 0644);

        // **RETURNS FILE NAME ONLY** (on stockera en DB juste le nom)
        return $fileName;
    }


    /**
     * Détermine le type de média basé sur l'extension du fichier
     * @method determinerTypeMedia
     * @access private
     * @param string $mediaPath - Chemin du fichier média
     *
     * @return string Type de média ('image', 'video', 'audio', 'none')
     *
     * @classification
     * - Images: jpg, jpeg, png, gif, webp
     * - Vidéos: mp4, avi, mov, mkv
     * - Audios: mp3, wav, ogg
     * - Autres: none
     */
    private function determinerTypeMedia($mediaPath)
    {
        $extension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return 'image';
        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'webm', 'mkv'])) {
            return 'video';
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
            return 'audio';
        } else {
            return 'none';
        }
    }


    /**
     * Supprime l'ancien fichier média lors d'une mise à jour
     * @method supprimerAncienMedia
     * @access private
     * @param int $articleId - ID de l'article à mettre à jour
     *
     * @action Récupère l'article et supprime son média associé
     * @principle Évite l'accumulation de fichiers inutilisés
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
     * @method supprimerFichierMedia
     * @access private
     * @param string $mediaPath - Chemin relatif du fichier à supprimer
     *
     * @security Vérifie l'existence du fichier avant suppression
     * @warning Action irréversible - les données sont perdues
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

    // ========================= MÉTHODES PUBLIQUES (CRUD PRINCIPAL) =========================

    /**
     * Ajoute un nouvel article dans la base de données
     * @method ajouterArticle
     * @access public
     * @param string $titre - Titre de l'article (255 caractères max)
     * @param string $contenu - Contenu textuel de l'article (texte long)
     * @param int $auteurId - ID de l'utilisateur auteur de l'article
     * @param array|null $fichier - Fichier média optionnel à uploader
     *
     * @sql INSERT INTO articles (titre, contenu, auteur_id, media_path, media_type, date_publication) VALUES (?, ?, ?, ?, ?, NOW())
     *
     * @process
     * 1. Upload du média si fourni
     * 2. Détermination du type de média
     * 3. Insertion en base avec tous les champs
     *
     * @return object|false Résultat de la requête SQL ou false en cas d'erreur
     *
     * @example
     * $result = $post->ajouterArticle("Mon Titre", "Mon contenu", 1, $_FILES['media']);
     */
    public function ajouterArticle($titre, $contenu, $auteurId, $fichier = null)
    {
        $mediaFileName = null;
        $mediaType = 'none';

        if ($fichier && $fichier['error'] === UPLOAD_ERR_OK) {
            $mediaFileName = $this->uploadMedia($fichier);
            if ($mediaFileName) {
                $mediaType = $this->determinerTypeMedia($mediaFileName); // renvoie 'image'|'video'|'audio'|'none'
                if (!in_array($mediaType, ['image', 'video', 'audio', 'none'])) {
                    $mediaType = 'none';
                }
            }
        }

        $sql = "INSERT INTO articles (titre, contenu, auteur_id, media_path, media_type, date_publication)
            VALUES (?, ?, ?, ?, ?, NOW())";

        // ici on stocke le filename seulement (ex: 1758632090_xxx.png)
        return $this->conn->executerRequete($sql, [
            $titre,
            $contenu,
            $auteurId,
            $mediaFileName,
            $mediaType
        ]);
    }


    /**
     * Modifie un article existant dans la base de données
     * @method modifierArticle
     * @access public
     * @param int $id - ID de l'article à modifier
     * @param string $titre - Nouveau titre de l'article
     * @param string $contenu - Nouveau contenu de l'article
     * @param array|null $fichier - Nouveau fichier média optionnel
     *
     * @process
     * 1. Upload du nouveau média si fourni
     * 2. Suppression de l'ancien média
     * 3. Mise à jour des champs en base
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
     * @method supprimerArticle
     * @access public
     * @param int $id - ID de l'article à supprimer
     *
     * @process
     * 1. Récupération des informations de l'article
     * 2. Suppression du fichier média physique
     * 3. Suppression de l'enregistrement en base
     *
     * @security Vérifie l'existence de l'article avant suppression
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
     * @method voirArticles
     * @access public
     *
     * @sql SELECT a.*, u.nom AS auteur FROM articles a JOIN utilisateurs u ON a.auteur_id = u.id ORDER BY a.date_publication DESC
     *
     * @join utilisateurs u - Jointure pour récupérer le nom de l'auteur
     * @order DESC - Articles les plus récents en premier
     *
     * @return array Tableau associatif de tous les articles ou tableau vide
     * @format [
     *     [
     *         'id' => 1,
     *         'titre' => 'Titre article',
     *         'contenu' => 'Contenu article',
     *         'auteur_id' => 1,
     *         'media_path' => 'chemin/vers/media.jpg',
     *         'media_type' => 'image',
     *         'date_publication' => '2024-01-15 10:30:00',
     *         'auteur' => 'John Doe'
     *     ],
     *     ...
     * ]
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
     * @method rechercherArticle
     * @access public
     * @param string $motCle - Mot-clé à rechercher
     *
     * @sql SELECT a.*, u.nom AS auteur FROM articles a JOIN utilisateurs u ON a.auteur_id = u.id WHERE a.titre LIKE ? OR a.contenu LIKE ? ORDER BY a.date_publication DESC
     *
     * @search Recherche full-text avec opérateur LIKE
     * @warning Peut être lent sur de grandes bases - envisager FULLTEXT INDEX
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
     * @method getArticleById
     * @access public
     * @param int $id - ID de l'article à récupérer
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

    /**
     * Récupère les articles par auteur spécifique
     * @method getArticlesByAuteur
     * @access public
     * @param int $auteurId - ID de l'auteur
     *
     * @return array Articles de l'auteur spécifié triés par date décroissante
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
     * Compte le nombre total d'articles dans la base
     * @method compterArticles
     * @access public
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

// ========================= NOTES TECHNIQUES =========================
/**
 * STRUCTURE DE LA TABLE ARTICLES (Recommandée) :
 *
 * CREATE TABLE articles (
 *     id INT PRIMARY KEY AUTO_INCREMENT,
 *     titre VARCHAR(255) NOT NULL,
 *     contenu TEXT NOT NULL,
 *     auteur_id INT NOT NULL,
 *     media_path VARCHAR(500) NULL,
 *     media_type ENUM('none', 'image', 'video', 'audio') DEFAULT 'none',
 *     date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
 *     date_modification DATETIME NULL,
 *     FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
 *     INDEX idx_auteur (auteur_id),
 *     INDEX idx_date (date_publication),
 *     FULLTEXT idx_recherche (titre, contenu)
 * );
 *
 * AMÉLIORATIONS FUTURES :
 * - [ ] Ajouter la pagination pour les méthodes de listing
 * - [ ] Implémenter le système de catégories/tags
 * - [ ] Ajouter les métadonnées SEO (meta description, keywords)
 * - [ ] Implémenter le système de brouillon/publication
 * - [ ] Ajouter les statistiques de vues/likes
 * - [ ] Implémenter la recherche avancée avec filtres
 *
 * CONSIDÉRATIONS SÉCURITÉ :
 * - [ ] Validation avancée des types MIME des fichiers
 * - [ ] Limitation de la taille des fichiers par type
 * - [ ] Sanitization du contenu HTML si autorisé
 * - [ ] Vérification des droits utilisateur sur les actions
 * - [ ] Logging des actions sensibles (suppression, modification)
 *
 * ⚡ OPTIMISATIONS PERFORMANCE :
 * - [ ] Cache des résultats des méthodes de listing
 * - [ ] Indexation FULLTEXT pour la recherche
 * - [ ] Compression automatique des images uploadées
 * - [ ] CDN pour le stockage des médias
 */
