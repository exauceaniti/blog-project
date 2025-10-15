<?php
/**
 * Classe Commentaire
 * Gère toutes les opérations liées aux commentaires (CRUD)
 */


require_once __DIR__ . '/../config/connexion.php';

class Commentaire
{
    /** @var Connexion $db Objet Connexion (qui gère PDO)
     */
    private $db;

    /** @var PDOStatement $conn Objet PDOStatement (qui gère PDO)
     */
    private $conn;

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
     * Ajouter un commentaire
     * @param string $contenu - Texte du commentaire
     * @param int $articleId - ID de l’article concerné
     * @param int $auteurId - ID de l’utilisateur auteur
     * @return bool - Résultat de la requête
     */
    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, date_commentaire)
                VALUES (?, ?, ?, NOW())";
        return $this->db->executerRequete($sql, [$contenu, $articleId, $auteurId]);
    }

    /**
     * Supprimer un commentaire
     * @param int $id - ID du commentaire à supprimer
     * @return bool - Résultat de la suppression
     */
    public function supprimerCommentaire($id)
    {
        $sql = "DELETE FROM commentaires WHERE id = ?";
        return $this->db->executerRequete($sql, [$id]);
    }



    /**
     * Voir les commentaires d’un article
     * @param int $articleId - ID de l’article
     * @return array - Liste des commentaires avec auteur et date
     */
    public function voirCommentaires($articleId)
    {
        $sql = "SELECT c.id, c.contenu, c.date_commentaire, u.nom AS auteur
                FROM commentaires c
                JOIN utilisateurs u ON c.auteur_id = u.id
                WHERE c.article_id = ?
                ORDER BY c.date_commentaire DESC";

        $stmt = $this->db->executerRequete($sql, [$articleId]);
        return $stmt->fetchAll();
    }



    /**
     * Récupérer un commentaire précis (utile pour édition ou vérification)
     * @param int $id - ID du commentaire
     * @return array|null - Détails du commentaire ou null si inexistant
     */
    public function getCommentaireById($id)
    {
        $sql = "SELECT * FROM commentaires WHERE id = ?";
        $stmt = $this->db->executerRequete($sql, [$id]);
        return $stmt->fetch() ?: null;
    }



    /**
     * Modifier un commentaire (si l’utilisateur est l’auteur)
     * @param int $id - ID du commentaire
     * @param string $contenu - Nouveau contenu
     * @param int $auteurId - ID de l’auteur (sécurité)
     * @return bool - Résultat de la mise à jour
     */
    public function modifierCommentaire($id, $contenu, $auteurId)
    {
        $sql = "UPDATE commentaires 
                SET contenu = ?, date_commentaire = NOW() 
                WHERE id = ? AND auteur_id = ?";
        return $this->db->executerRequete($sql, [$contenu, $id, $auteurId]);
    }



    /**
     * Compter le nombre total de commentaires
     * @return int
     */
    public function countAllComments()
    {
        $sql = "SELECT COUNT(*) AS total FROM commentaires";
        $stmt = $this->db->executerRequete($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }



    /**
     * Compter les commentaires d’un article précis
     * @param int $articleId
     * @return int
     */
    public function countCommentsByArticle($articleId)
    {
        $sql = "SELECT COUNT(*) AS total FROM commentaires WHERE article_id = ?";
        $stmt = $this->db->executerRequete($sql, [$articleId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Voir tous les commentaires avec détails (pour admin)
     * @return array - Liste complète des commentaires avec auteurs et articles
     */
    public function voirCommentairesGlobal()
    {
        $sql = "SELECT c.id, c.contenu, c.date_commentaire, u.nom AS auteur, a.titre AS article
            FROM commentaires c
            JOIN utilisateurs u ON c.auteur_id = u.id
            JOIN articles a ON c.article_id = a.id
            ORDER BY c.date_commentaire DESC";
        $stmt = $this->db->executerRequete($sql);
        return $stmt->fetchAll();
    }

}
