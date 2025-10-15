<?php
/**
 * Controller : CommentController
 * Gère les actions sur les commentaires (affichage, ajout, suppression, édition)
 * 
 * @author
 *  Exauce Aniti
 */

require_once __DIR__ . '/../models/Commentaire.php';

class CommentController
{
    private $model;

    /**
     * Constructeur
     * @param Connexion $connexion
     */
    public function __construct($connexion)
    {
        // Création du modèle commentaire avec la connexion
        $this->model = new Commentaire($connexion);
    }

    /**
     * Afficher tous les commentaires
     * @return array
     */
    public function afficherCommentaires()
    {
        try {
            //cette methode je vais l'appliquer dans la classe de l'administrateur 
            return $this->model->voirCommentairesGlobal();
        } catch (Exception $e) {
            echo "<p style='color:red;'> Impossible d'afficher les commentaires : " . htmlspecialchars($e->getMessage()) . "</p>";
            return [];
        }
    }

    /**
     * Afficher les commentaires d’un article
     * @param int $articleId
     * @return array
     */
    public function afficherCommentairesParArticle($articleId)
    {
        try {
            return $this->model->voirCommentaires($articleId);
        } catch (Exception $e) {
            echo "<p style='color:red;'> Erreur : impossible de charger les commentaires.</p>";
            return [];
        }
    }

    /**
     * Ajouter un commentaire
     * @param string $contenu
     * @param int $articleId
     * @param int $auteurId
     */
    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        if (empty($contenu) || !$articleId) {
            echo "<p style='color:red;'>  Veuillez remplir tous les champs du commentaire.</p>";
            return;
        }

        try {
            $this->model->ajouterCommentaire($contenu, $articleId, $auteurId);
            echo "<p style='color:green;'> Commentaire ajouté avec succès !</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'> Erreur lors de l’ajout du commentaire.</p>";
        }
    }

    /**
     * Modifier un commentaire
     * @param int $id
     * @param string $contenu
     * @param int $auteurId
     */
    public function modifierCommentaire($id, $contenu, $auteurId)
    {
        if (empty($contenu)) {
            echo "<p style='color:red;'> Le contenu du commentaire ne peut pas être vide.</p>";
            return;
        }

        try {
            $this->model->modifierCommentaire($id, $contenu, $auteurId);
            echo "<p style='color:green;'>✏️ Commentaire modifié avec succès.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'> Impossible de modifier le commentaire.</p>";
        }
    }

    /**
     * Supprimer un commentaire
     * @param int $id
     */
    public function supprimerCommentaire($id)
    {
        if (!$id) {
            echo "<p style='color:red;'> Identifiant manquant pour la suppression.</p>";
            return;
        }

        try {
            $this->model->supprimerCommentaire($id);
            echo "<p style='color:green;'>🗑️ Commentaire supprimé avec succès.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'> Erreur lors de la suppression du commentaire.</p>";
        }
    }

    /**
     * Compter le nombre total de commentaires
     * @return int
     */
    public function compterCommentaires()
    {
        try {
            return $this->model->countAllComments();
        } catch (Exception $e) {
            echo "<p style='color:red;'> Impossible de compter les commentaires.</p>";
            return 0;
        }
    }
}
