<?php

/**
 * Classe Post
 *
 * Cette classe gère les opérations CRUD (Créer, Lire, Mettre à jour, Supprimer)
 * pour les articles d’un blog. Elle utilise un objet Connexion pour interagir
 * avec la base de données.
 */
class Post
{
    /**
     * @var Connexion $conn Objet de connexion à la base de données
     */
    private $conn;

    /**
     * Constructeur
     *
     * Initialise la classe Post avec un objet Connexion.
     *
     * @param Connexion $connexion Objet de connexion à la base de données
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    /**
     * Ajouter un nouvel article avec média (image/vidéo optionnel)
     *
     * @param string $titre     Titre de l'article
     * @param string $contenu   Contenu de l'article
     * @param int    $auteurId  ID de l'auteur
     * @param array  $fichier   Tableau $_FILES['media'] (facultatif)
     *
     * @return PDOStatement Résultat de l'insertion
     */
    public function ajouterArticle($titre, $contenu, $auteurId, $fichier = null)
    {
        $mediaPath = null;

        // Vérifie si un fichier a été uploadé
        if ($fichier && isset($fichier['tmp_name']) && $fichier['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Sécuriser le nom du fichier
            $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($fichier['name']));
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($fichier['tmp_name'], $filePath)) {
                $mediaPath = "uploads/" . $fileName; // chemin relatif
            }
        }

        // Insertion SQL avec média
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, media, date_Publication)
            VALUES (?, ?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $auteurId, $mediaPath]);
    }

    /**
     * Modifier un article existant (titre, contenu et éventuellement média)
     *
     * @param int    $id       ID de l'article
     * @param string $titre    Nouveau titre
     * @param string $contenu  Nouveau contenu
     * @param array  $fichier  Tableau $_FILES['media'] (facultatif)
     *
     * @return PDOStatement Résultat de la mise à jour
     */
    public function modifierArticle($id, $titre, $contenu, $fichier = null)
    {
        $params = [$titre, $contenu, $id];
        $sql = "UPDATE articles SET titre = ?, contenu = ?";

        // Vérifie si un nouveau fichier a été uploadé
        if ($fichier && isset($fichier['tmp_name']) && $fichier['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Sécuriser le nom du fichier
            $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($fichier['name']));
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($fichier['tmp_name'], $filePath)) {
                $mediaPath = "uploads/" . $fileName;
                $sql .= ", media = ?";
                $params = [$titre, $contenu, $mediaPath, $id];
            }
        }

        $sql .= " WHERE id = ?";
        return $this->conn->executerRequete($sql, $params);
    }

    /**
     * Supprimer un article
     *
     * @param int $id ID de l'article à supprimer
     * @return PDOStatement Résultat de la suppression
     */
    public function supprimerArticle($id)
    {
        $sql = "DELETE FROM articles WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }

    /**
     * Rechercher des articles par mot-clé (dans le titre ou le contenu).
     *
     * @param string $motCle Mot-clé à rechercher
     * @return array Liste des articles correspondants (peut être vide si aucun résultat)
     */
    public function rechercherArticle($motCle)
    {
        $sql = "SELECT a.*, u.nom AS auteur
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                WHERE a.titre LIKE ?
                   OR a.contenu LIKE ?
                ORDER BY a.date_publication DESC";

        // Exécution avec deux paramètres identiques (titre et contenu)
        $stmt = $this->conn->executerRequete($sql, ["%$motCle%", "%$motCle%"]);

        return $stmt->fetchAll() ?: [];
    }

    /**
     * Récupérer ou voir tous les articles
     *
     * Joint les utilisateurs pour récupérer le nom de l'auteur
     * et ordonne par date de publication décroissante.
     *
     * @return array Tableau associatif des articles
     */
    public function voirArticles()
    {
        $sql = "SELECT a.*, u.nom AS auteur
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                ORDER BY a.date_publication DESC";
        return $this->conn->executerRequete($sql)->fetchAll();
    }

    /**
     * Récupérer un article par son ID
     *
     * @param int $id ID de l'article
     * @return array|null Tableau associatif de l'article ou null si non trouvé
     */
    public function getArticleById($id)
    {
        $sql = "SELECT a.*, u.nom AS auteur
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                WHERE a.id = ?";
        return $this->conn->executerRequete($sql, [$id])->fetch() ?: null;
    }
}
