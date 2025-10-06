<?php

require_once __DIR__ . '/../config/connexion.php';

/**
 * Classe Post
 * 
 * Gère toutes les opérations liées aux articles dans la base de données.
 */
class Post
{
    /**
     * @var Connexion $db Objet Connexion (qui gère PDO)
     */
    private $db;

    /**
     * Constructeur
     * 
     * @param Connexion $connexion Instance de la classe Connexion
     * @throws Exception si la connexion n'est pas initialisée
     */
    public function __construct($connexion)
    {
        if (!$connexion instanceof Connexion) {
            throw new Exception("La connexion à la base de données doit être une instance de Connexion !");
        }
        $this->db = $connexion;
    }

    /**
     * Ajouter un nouvel article
     */
    public function ajouterArticle($titre, $contenu, $auteurId, $mediaPath = null, $mediaType = null): bool
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, media_path, media_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->executerRequete($sql, [$titre, $contenu, $auteurId, $mediaPath, $mediaType]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Modifier un article existant
     */
    public function modifierArticle($id, $titre, $contenu, $auteurId, $mediaPath = null, $mediaType = null): bool
    {
        $sql = "UPDATE articles 
                SET titre = ?, contenu = ?, auteur_id = ?, media_path = ?, media_type = ? 
                WHERE id = ?";
        $stmt = $this->db->executerRequete($sql, [$titre, $contenu, $auteurId, $mediaPath, $mediaType, $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Supprimer un article par ID
     */
    public function supprimerArticle($id): bool
    {
        $sql = "DELETE FROM articles WHERE id = ?";
        $stmt = $this->db->executerRequete($sql, [$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Voir un article par ID
     */
    public function voirArticle($id)
    {
        $sql = "SELECT * FROM articles WHERE id = ?";
        return $this->db->executerRequete($sql, [$id])->fetch();
    }

    /**
     * Récupérer tous les articles avec info auteur
     */
    public function getAllArticles()
    {
        $sql = "SELECT 
                    a.id, a.titre, a.contenu, a.media_path, a.media_type, a.date_publication,
                    au.nom AS auteur_nom, au.email AS auteur_email
                FROM articles a
                JOIN auteur au ON a.auteur_id = au.id
                ORDER BY a.date_publication DESC";
        return $this->db->executerRequete($sql)->fetchAll();
    }

    /**
     * Rechercher articles par mot-clé (titre ou contenu)
     */
    public function rechercherArticle($motCle)
    {
        $keywords = explode(' ', trim($motCle));
        $conditions = [];
        $params = [];

        foreach ($keywords as $word) {
            $conditions[] = "(a.titre LIKE ? OR a.contenu LIKE ?)";
            $params[] = "%$word%";
            $params[] = "%$word%";
        }

        $sql = "SELECT a.id, a.titre, a.contenu, a.media_path, a.media_type, a.date_publication,
                       au.nom AS auteur_nom, au.email AS auteur_email
                FROM articles a
                JOIN auteur au ON a.auteur_id = au.id";

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' OR ', $conditions);
        }

        $sql .= " ORDER BY a.date_publication DESC";

        return $this->db->executerRequete($sql, $params)->fetchAll();
    }

    /**
     * Récupérer les articles d'un auteur
     */
    public function rechercherArticleParAuteur($auteurId)
    {
        $sql = "SELECT a.id, a.titre, a.contenu, a.media_path, a.media_type, a.date_publication,
                       au.nom AS auteur_nom, au.email AS auteur_email
                FROM articles a
                JOIN auteur au ON a.auteur_id = au.id
                WHERE a.auteur_id = ?
                ORDER BY a.date_publication DESC";

        return $this->db->executerRequete($sql, [$auteurId])->fetchAll();
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
     * Compter tous les articles d'un auteur
     */
    public function countAllArticlesParAuteur($auteurId): int
    {
        $sql = "SELECT COUNT(*) FROM articles WHERE auteur_id = ?";
        return (int) $this->db->executerRequete($sql, [$auteurId])->fetchColumn();
    }

    /**
     * Pagination des articles
     */
    public function getArticlesPagines($limit, $offset)
    {
        $pdo = $this->db->connecter();

        $sql = "SELECT 
                a.id, a.titre, a.contenu, a.media_path, a.media_type, a.date_publication,
                u.nom AS auteur_nom, u.email AS auteur_email
            FROM articles a
            JOIN utilisateurs u ON a.auteur_id = u.id
            ORDER BY a.date_publication DESC
            LIMIT :limit OFFSET :offset";

        $requete = $pdo->prepare($sql);
        $requete->bindValue(':limit', $limit, PDO::PARAM_INT);
        $requete->bindValue(':offset', $offset, PDO::PARAM_INT);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }


}
