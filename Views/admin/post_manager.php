<?php

/**
 * views/admin/post_management.php
 * Vue de gestion pour l'administrateur.
 * Reçoit $articles_list du PostController::adminIndex().
 */
$articles_list = $articles_list ?? [];
?>

<h2>⚙️ Gestion des Articles</h2>
<p><a href="/admin/posts/new" class="btn-primary">Ajouter un Nouvel Article</a></p>

<?php if (!empty($articles_list)): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Médias</th>
                <th>Commentaires</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles_list as $article): ?>
                <tr>
                    <td><?= htmlspecialchars($article->id) ?></td>
                    <td>
                        <a href="/articles/<?= htmlspecialchars($article->id) ?>" target="_blank">
                            <?= substr(htmlspecialchars($article->titre), 0, 50) ?>...
                        </a>
                    </td>
                    <td><?= htmlspecialchars($article->auteur_nom) ?></td>
                    <td><?= date('d/m/Y', strtotime($article->date_publication)) ?></td>
                    <td><?= !empty($article->media_path) ? '✅' : '❌' ?></td>
                    <td><?= $article->comment_count ?></td>
                    <td class="action-buttons">
                        <a href="/admin/posts/edit/<?= $article->id ?>" class="btn-edit">Modifier</a>

                        <form action="/post/delete/<?= $article->id ?>" method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun article à gérer pour le moment.</p>
<?php endif; ?>