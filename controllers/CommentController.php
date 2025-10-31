<?php
require_once __DIR__ . '/../models/Commentaire.php';

class CommentController
{
    private $commentModel;

    public function __construct($connexion)
    {
        $this->commentModel = new Commentaire($connexion);
    }

    /**
     * 🔹 Affiche les commentaires pour la page admin
     * Route : admin/manage_comments
     */
    public function manageComments()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return ['redirect' => 'admin/login'];
        }

        try {
            $comments = $this->commentModel->voirCommentairesGlobal();
            return ['view' => 'admin/manage_comments', 'data' => ['comments' => $comments]];
        } catch (Exception $e) {
            return ['view' => 'admin/manage_comments', 'data' => ['comments' => [], 'error' => 'Erreur de chargement']];
        }
    }

    /**
     * Summary of afficherCommentairesParArticle
     * ici on associe a chaque commentaire des articles s'il existe
     * ou s'il n'existe pas on laisse la case vide
     * @param mixed $articleId
     * @return array
     */

    public function afficherCommentairesParArticle($articleId)
    {
        try {
            return $this->commentModel->voirCommentaires($articleId);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Summary of ajouterCommentaire
     * Ici c'est une methode pour ajouter un commentaire a un article
     * @param mixed $contenu
     * @param mixed $articleId
     * @param mixed $auteurId
     * @return array{message: string, status: string}
     */
    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        if (empty($contenu) || !$articleId) {
            return ['status' => 'error', 'message' => 'Champs du commentaire manquants.'];
        }

        try {
            $this->commentModel->ajouterCommentaire($contenu, $articleId, $auteurId);
            return ['status' => 'success', 'message' => 'Commentaire ajouté avec succès.'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Erreur lors de l’ajout du commentaire.'];
        }
    }

    public function modifierCommentaire($id, $contenu, $auteurId)
    {
        if (empty($contenu)) {
            return ['status' => 'error', 'message' => 'Le contenu ne peut pas être vide.'];
        }

        try {
            $this->commentModel->modifierCommentaire($id, $contenu, $auteurId);
            return ['status' => 'success', 'message' => 'Commentaire modifié avec succès.'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Impossible de modifier le commentaire.'];
        }
    }

    /**
     * Summary of supprimerCommentaire
     * Methode pour supprimer un commentaire.
     * cette methode ne sera appliquer que dans la page de l'administrateur
     * @param mixed $id
     * @return array{message: string, status: string}
     */

    public function supprimerCommentaire($id)
    {
        if (!$id) {
            return ['status' => 'error', 'message' => 'Identifiant manquant.'];
        }

        try {
            $this->commentModel->supprimerCommentaire($id);
            return ['status' => 'success', 'message' => 'Commentaire supprimé avec succès.'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Erreur lors de la suppression.'];
        }
    }


    /**
     * Compte le nombre total de commentaires.
     * Cette methode compte tout les commentaire qui se trouve dans la base de donnees
     * nous l'appellerons dans le dashboard de l'administrateur.
     * @return int
     */
    public function compterCommentaires()
    {
        try {
            return $this->commentModel->countAllComments();
        } catch (Exception $e) {
            return 0;
        }
    }
}
