<?php
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/CommentController.php';

// Connexion et initialisation du contrôleur
$connexion = new Connexion();
$controller = new CommentController($connexion);

// Récupérer tous les commentaires
$commentaires = $controller->afficherCommentairesParArticle($articleId);

// Messages simples (succès / erreur)
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<section class="admin-section">
    <h2>💬 Gestion des Commentaires</h2>

    <?php if ($message): ?>
        <div style="color: green; margin-bottom: 10px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Tableau des commentaires -->
    <table id="table-comments">
        <thead>
            <tr>
                <th>ID</th>
                <th>Article ID</th>
                <th>Auteur</th>
                <th>Contenu</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commentaires as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= $c['article_id'] ?></td>
                    <td><?= htmlspecialchars($c['auteur']) ?></td>
                    <td><?= htmlspecialchars(substr($c['contenu'], 0, 100)) ?>...</td>
                    <td><?= $c['date_Commentaire'] ?></td>
                    <td>
                        <!-- Supprimer commentaire -->
                        <form action="/index.php?route=admin/manage_comment_action" method="POST"
                            style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" name="supprimer-commentaire"
                                onclick="return confirm('Voulez-vous vraiment supprimer ce commentaire ?');">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<style>
    .admin-section {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        font-family: Arial, sans-serif;
    }

    #table-comments {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    #table-comments th,
    #table-comments td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    #table-comments th {
        background: #2c3e50;
        color: white;
    }

    #table-comments tr:hover {
        background: #f0f0f0;
    }

    #table-comments button {
        padding: 5px 10px;
        border: none;
        background-color: #c0392b;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }

    #table-comments button:hover {
        background-color: #e74c3c;
    }
</style>