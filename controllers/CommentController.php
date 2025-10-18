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

    public function afficherCommentairesParArticle($articleId)
    {
        try {
            return $this->commentModel->voirCommentaires($articleId);
        } catch (Exception $e) {
            return [];
        }
    }

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

    public function compterCommentaires()
    {
        try {
            return $this->commentModel->countAllComments();
        } catch (Exception $e) {
            return 0;
        }
    }
}
