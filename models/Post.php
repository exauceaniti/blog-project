<?php

namespace models;

use Connexion;
use PDO;
use Exception;

require_once __DIR__ . '/../config/connexion.php';

/**
 * Classe Post
 * 
 * Gère toutes les opérations liées aux articles dans la base de données.
 */
class Post
{
    /**
     * @var Connexion Instance de connexion à la base de données
     */
    private Connexion $db;

    /**
     * Constructeur
     * Initialise la connexion via le singleton Connexion
     * 
     * @throws Exception si la connexion échoue
     */
    public function __construct()
    {
        $this->db = Connexion::getInstance();
    }

    /**
     * Ajouter un nouvel article
     */
    public function ajouterArticle(string $titre, string $contenu, int $auteurId, ?string $mediaPath = null, ?string $mediaType = null): bool
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, media_path, media_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->executerRequete($sql, [$titre, $contenu, $auteurId, $mediaPath, $mediaType]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Modifier un article existant
     */
    public function modifierArticle(int $id, string $titre, string $contenu, int $auteurId, ?string $mediaPath = null, ?string $mediaType = null): bool
    {
        if ($mediaPath) {
            $sql = "UPDATE articles SET titre = ?, contenu = ?, auteur_id = ?, media_path = ?, media_type = ?, updated_at = NOW() WHERE id = ?";
            $params = [$titre, $contenu, $auteurId, $mediaPath, $mediaType, $id];
        } else {
            $sql = "UPDATE articles SET titre = ?, contenu = ?, auteur_id = ?, updated_at = NOW() WHERE id = ?";
            $params = [$titre, $contenu, $auteurId, $id];
        }

        $stmt = $this->db->executerRequete($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    /**
     * Supprimer un article par ID
     */
    public function supprimerArticle(int $id): bool
    {
        $sql = "DELETE FROM articles WHERE id = ?";
        $stmt = $this->db->executerRequete($sql, [$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Voir un article par ID
     */
    public function voirArticle(int $id): array|false
    {
        $sql = "SELECT * FROM articles WHERE id = ?";
        return $this->db->executerRequete($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les articles avec info auteur
     */
    public function getAllArticles(): array
    {
        $sql = "SELECT 
                    a.id, a.titre, a.contenu, a.media_path, a.media_type, a.date_publication,
                    u.nom AS auteur_nom, u.email AS auteur_email
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                ORDER BY a.date_publication DESC";

        return $this->db->executerRequete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Rechercher des articles par mot-clé
     */
    public function rechercherArticle(string $motCle): array
    {
        $keywords = explode(' ', trim($motCle));
        $conditions = [];
        $params = [];

        foreach ($keywords as $word) {
            $conditions[] = "(a.titre LIKE ? OR a.contenu LIKE ?)";
            $params[] = "%$word%";
            $params[] = "%$word%";
        }

        $sql = "SELECT 
                    a.id, a.titre, a.contenu, a.media_path, a.media_type, a.date_publication,
                    u.nom AS auteur_nom, u.email AS auteur_email
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id";

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' OR ', $conditions);
        }

        $sql .= " ORDER BY a.date_publication DESC";

        return $this->db->executerRequete($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les articles d’un auteur
     */
    public function rechercherArticleParAuteur(int $auteurId): array
    {
        $sql = "SELECT 
                    a.id, a.titre, a.contenu, a.media_path, a.media_type, a.date_publication,
                    u.nom AS auteur_nom, u.email AS auteur_email
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                WHERE a.auteur_id = ?
                ORDER BY a.date_publication DESC";

        return $this->db->executerRequete($sql, [$auteurId])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter tous les articles
     */
    public function countAllArticles(): int
    {
        $sql = "SELECT COUNT(*) FROM articles";
        return (int) $this->db->executerRequete($sql)->fetchColumn();
    }

    /**
     * Compter les articles d’un auteur
     */
    public function countAllArticlesParAuteur(int $auteurId): int
    {
        $sql = "SELECT COUNT(*) FROM articles WHERE auteur_id = ?";
        return (int) $this->db->executerRequete($sql, [$auteurId])->fetchColumn();
    }

    /**
     * Récupérer les articles récents
     */
    public function getRecentArticles(int $limit = 5): array
    {
        $sql = "SELECT 
                    a.id, a.titre, a.date_publication,
                    u.nom AS auteur_nom
                FROM articles a
                JOIN utilisateurs u ON a.auteur_id = u.id
                ORDER BY a.date_publication DESC
                LIMIT ?";

        return $this->db->executerRequete($sql, [$limit])->fetchAll(PDO::FETCH_ASSOC);
    }
}
