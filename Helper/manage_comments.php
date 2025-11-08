<?php
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/CommentController.php';

$connexion = Connexion::getInstance();
$controller = new CommentController($connexion);

// Récupérer tous les commentaires
$comments = $controller->manageComments()['data']['comments'];
?>

<section class="admin-section">
    <h2> Gestion des Commentaires</h2>

    <!-- Tableau des commentaires -->
    <table id="table-comments">
        <thead>
            <tr>
                <th>ID</th>
                <th>Auteur</th>
                <th>Article</th>
                <th>Contenu</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $c): ?>
                <tr>
                    <td>
                        <?= $c['id'] ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($c['auteur']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($c['article']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars(substr($c['contenu'], 0, 100)) ?>...
                    </td>
                    <td>
                        <?= $c['date_commentaire'] ?>
                    </td>
                    <td>
                        <form method="POST" action="index.php?route=admin/manage_comment_action" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" name="supprimer-commentaire"
                                onclick="return confirm('Supprimer ce commentaire ?');">🗑️ Supprimer</button>
                        </form>
                        <a href="index.php?route=admin/edit_comment&id=<?= $c['id'] ?>">✏️ Modifier</a>
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

    button {
        background-color: #c0392b;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    a {
        margin-left: 10px;
        color: #2980b9;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>