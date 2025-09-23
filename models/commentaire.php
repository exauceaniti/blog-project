<?php

/**
 * @file Commentaire.php
 * @description Modèle de gestion des commentaires - Opérations CRUD sur les commentaires d'articles
 * @author [Exauce Aniti]
 * @version 1.0
 * @date 2024
 *
 * @package Models
 * @class commentaire
 *
 * @feature Ajout, suppression et consultation des commentaires
 * @security Gestion des relations utilisateurs-commentaires
 */

/**
 * CLASSE COMMENTAIRE - MODÈLE MÉTIER
 * @class commentaire
 * @description Gère toutes les interactions base de données pour les commentaires
 *
 * @property Connexion $conn - Instance de connexion à la base de données
 * @method ajouterCommentaire() - Création d'un nouveau commentaire
 * @method supprimerCommentaire() - Suppression d'un commentaire
 * @method voirCommentaires() - Récupération des commentaires d'un article
 */
class commentaire
{
    /**
     * Instance de connexion à la base de données
     * @var Connexion $conn
     * @access private
     * @description Gère l'exécution des requêtes SQL préparées
     */
    private $conn;

    // ========================= CONSTRUCTEUR =========================
    /**
     * Constructeur de la classe Commentaire
     * @constructor
     * @param Connexion $connexion - Instance injectée de connexion BDD
     *
     * @dependency Injection de dépendance pour une meilleure testabilité
     * @example $commentaire = new commentaire($connexion);
     *
     * @action Initialise la propriété $conn avec l'objet connexion
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    // ========================= MÉTHODE AJOUTER COMMENTAIRE =========================
    /**
     * Ajoute un nouveau commentaire à un article
     * @method ajouterCommentaire
     * @param string $contenu - Texte du commentaire (obligatoire)
     * @param int $articleId - ID de l'article cible (obligatoire)
     * @param int $auteurId - ID de l'utilisateur auteur (obligatoire)
     *
     * @sql INSERT INTO commentaires (contenu, article_id, auteur_id, date_Commentaire) VALUES (?, ?, ?, NOW())
     *
     * @validation Les paramètres doivent être non vides et valides
     * @security L'auteurId doit correspondre à un utilisateur authentifié
     *
     * @return PDOStatement|false
     * @success Retourne l'objet PDOStatement en cas de succès
     * @error Retourne false en cas d'échec de l'insertion
     *
     * @example
     * $result = $commentaire->ajouterCommentaire("Super article!", 15, 4);
     */
    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        /**
         * Requête SQL d'insertion avec paramètres préparés
         * @security Prévention des injections SQL via prepared statements
         * @field contenu - Texte du commentaire (VARCHAR/TEXT)
         * @field article_id - Clé étrangère vers la table articles (INT)
         * @field auteur_id - Clé étrangère vers la table utilisateurs (INT)
         * @field date_Commentaire - Horodatage automatique (DATETIME)
         */
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, date_Commentaire) VALUES (?, ?, ?, NOW())";

        /**
         * Exécution de la requête préparée
         * @param array [$contenu, $articleId, $auteurId] - Paramètres bindés
         * @return PDOStatement Résultat de l'exécution
         */
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $auteurId]);
    }

    // ========================= MÉTHODE SUPPRIMER COMMENTAIRE =========================
    /**
     * Supprime un commentaire de la base de données
     * @method supprimerCommentaire
     * @param int $id - ID unique du commentaire à supprimer (obligatoire)
     *
     * @sql DELETE FROM commentaires WHERE id = ?
     *
     * @validation L'ID doit exister en base de données
     * @security Vérifier que l'utilisateur est propriétaire ou admin
     *
     * @return PDOStatement|false
     * @success Retourne l'objet PDOStatement si suppression réussie
     * @error Retourne false si le commentaire n'existe pas
     *
     * @example
     * $result = $commentaire->supprimerCommentaire(42);
     */
    public function supprimerCommentaire($id)
    {
        /**
         * Requête SQL de suppression avec paramètre préparé
         * @security Suppression conditionnelle par ID pour éviter les accidents
         * @warning Cette action est irréversible
         */
        $sql = "DELETE FROM commentaires WHERE id = ?";

        /**
         * Exécution de la requête de suppression
         * @param array [$id] - ID du commentaire à supprimer
         * @return PDOStatement Résultat de la suppression
         */
        return $this->conn->executerRequete($sql, [$id]);
    }

    // ========================= MÉTHODE VOIR COMMENTAIRES =========================
    /**
     * Récupère tous les commentaires d'un article spécifique
     * @method voirCommentaires
     * @param int $articleId - ID de l'article concerné (obligatoire)
     *
     * @sql SELECT c.*, u.nom AS auteur FROM commentaires c JOIN utilisateurs u ON c.auteur_id = u.id WHERE c.article_id = ? ORDER BY c.date_Commentaire DESC
     *
     * @join utilisateurs u - Jointure pour récupérer le nom de l'auteur
     * @order DESC - Tri du plus récent au plus ancien
     *
     * @return array
     * @success Tableau associatif des commentaires avec informations auteur
     * @format [
     *     [
     *         'id' => 1,
     *         'contenu' => 'Texte du commentaire',
     *         'article_id' => 15,
     *         'auteur_id' => 4,
     *         'date_Commentaire' => '2024-01-15 10:30:00',
     *         'auteur' => 'John Doe'
     *     ],
     *     ...
     * ]
     *
     * @example
     * $commentaires = $commentaire->voirCommentaires(15);
     * foreach($commentaires as $comment) {
     *     echo $comment['auteur'] . ': ' . $comment['contenu'];
     * }
     */
    public function voirCommentaires($articleId)
    {
        /**
         * Requête SQL avec jointure et tri
         * @join JOIN utilisateurs u - Récupération du nom de l'auteur
         * @field c.* - Tous les champs de la table commentaires
         * @field u.nom AS auteur - Nom de l'utilisateur auteur
         * @order ORDER BY c.date_Commentaire DESC - Plus récents en premier
         */
        $sql = "SELECT c.*, u.nom AS auteur
                FROM commentaires c
                JOIN utilisateurs u ON c.auteur_id = u.id
                WHERE c.article_id = ?
                ORDER BY c.date_Commentaire DESC";

        /**
         * Exécution et récupération des résultats
         * @param array [$articleId] - ID de l'article cible
         * @return array Résultats sous forme de tableau associatif
         */
        return $this->conn->executerRequete($sql, [$articleId])->fetchAll();
    }
}

// ========================= NOTES TECHNIQUES =========================
/**
 * STRUCTURE DE LA TABLE COMMENTAIRES (Recommandée) :
 *
 * CREATE TABLE commentaires (
 *     id INT PRIMARY KEY AUTO_INCREMENT,
 *     contenu TEXT NOT NULL,
 *     article_id INT NOT NULL,
 *     auteur_id INT NOT NULL,
 *     date_Commentaire DATETIME DEFAULT CURRENT_TIMESTAMP,
 *     FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
 *     FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
 *     INDEX idx_article_id (article_id),
 *     INDEX idx_date (date_Commentaire)
 * );
 *
 * AMÉLIORATIONS FUTURES :
 * - [ ] Ajouter la méthode modifierCommentaire()
 * - [ ] Implémenter la pagination pour les articles avec nombreux commentaires
 * - [ ] Ajouter les réponses aux commentaires (système de threads)
 * - [ ] Implémenter le vote/like sur les commentaires
 * - [ ] Ajouter la modération automatique (filtrage mots interdits)
 *
 * CONSIDÉRATIONS SÉCURITÉ :
 * - [ ] Sanitization du contenu avant insertion
 * - [ ] Validation de la longueur du commentaire
 * - [ ] Vérification que l'articleId existe bien
 * - [ ] Vérification des droits de suppression
 */
