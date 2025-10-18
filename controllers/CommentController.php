<?php
/**
 * Controller : CommentController
 * Gère les actions sur les commentaires (affichage, ajout, suppression, édition)
 * @author Exauce Aniti
 */

require_once __DIR__ . '/../models/Commentaire.php';

class CommentController
{
    private $commentModel;

    public function __construct($connexion)
    {
        $this->commentModel = new Commentaire($connexion);
    }

    /**
     * 🔹 Affiche la page admin de gestion des commentaires
     * Route : admin/manage_comments
     */
    public function manageComments()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?route=admin/login');
            exit;
        }

        $comments = $this->commentModel->voirCommentairesGlobal(); // méthode déjà existante
        require_once __DIR__ . '/../views/admin/manage_comments.php';
    }

    public function afficherCommentairesParArticle($articleId)
    {
        try {
            return $this->commentModel->voirCommentaires($articleId);
        } catch (Exception $e) {
            echo "<p style='color:red;'> Erreur : impossible de charger les commentaires.</p>";
            return [];
        }
    }

    public function ajouterCommentaire($contenu, $articleId, $auteurId)
    {
        if (empty($contenu) || !$articleId) {
            echo "<p style='color:red;'> Veuillez remplir tous les champs du commentaire.</p>";
            return;
        }

        try {
            $this->commentModel->ajouterCommentaire($contenu, $articleId, $auteurId);
            echo "<p style='color:green;'> Commentaire ajouté avec succès !</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'> Erreur lors de l’ajout du commentaire.</p>";
        }
    }

    public function modifierCommentaire($id, $contenu, $auteurId)
    {
        if (empty($contenu)) {
            echo "<p style='color:red;'> Le contenu du commentaire ne peut pas être vide.</p>";
            return;
        }

        try {
            $this->commentModel->modifierCommentaire($id, $contenu, $auteurId);
            echo "<p style='color:green;'>✏️ Commentaire modifié avec succès.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'> Impossible de modifier le commentaire.</p>";
        }
    }

    public function supprimerCommentaire($id)
    {
        if (!$id) {
            echo "<p style='color:red;'> Identifiant manquant pour la suppression.</p>";
            return;
        }

        try {
            $this->commentModel->supprimerCommentaire($id);
            echo "<p style='color:green;'>🗑️ Commentaire supprimé avec succès.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;'> Erreur lors de la suppression du commentaire.</p>";
        }
    }

    public function compterCommentaires()
    {
        try {
            return $this->commentModel->countAllComments();
        } catch (Exception $e) {
            echo "<p style='color:red;'> Impossible de compter les commentaires.</p>";
            return 0;
        }
    }
}
