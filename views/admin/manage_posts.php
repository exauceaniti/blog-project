<?php
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';

$connexion = new Connexion();
$controller = new PostController($connexion);

// Récupérer tous les articles
$posts = $controller->getArticlesForPage(1, 50);
?>

<section class="admin-section">
    <h2>📰 Gestion des Articles</h2>

    <!-- Message de succès -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div style="color:green; margin-bottom:10px;">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <section id="add-post">
        <h3>Ajouter un nouvel article</h3>
        <form action="/index.php?route=admin/manage_posts" method="POST" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre de l’article" required>
            <textarea name="contenu" placeholder="Contenu de l’article" required></textarea>
            <input type="file" name="media" accept="image/*,video/*,audio/*">
            <button type="submit" name="ajouter-article">Publier</button>
        </form>
    </section>

    <!-- Tableau des articles -->
    <table id="table-posts">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Média</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['titre']) ?></td>
                    <td><?= htmlspecialchars(substr($p['contenu'], 0, 100)) ?>...</td>
                    <td>
                        <?php if (!empty($p['media_path'])):
                            $type = $p['media_type'] ?? '';
                            $mediaSrc = '/' . ltrim($p['media_path'], '/');
                            if (str_contains($type, 'image')): ?>
                                <img src="<?= htmlspecialchars($mediaSrc) ?>" width="120">
                            <?php elseif (str_contains($type, 'video')): ?>
                                <video width="200" controls>
                                    <source src="<?= htmlspecialchars($mediaSrc) ?>" type="<?= htmlspecialchars($type) ?>">
                                </video>
                            <?php elseif (str_contains($type, 'audio')): ?>
                                <audio controls>
                                    <source src="<?= htmlspecialchars($mediaSrc) ?>" type="<?= htmlspecialchars($type) ?>">
                                </audio>
                            <?php endif;
                        endif; ?>
                    </td>
                    <td><?= $p['date_publication'] ?></td>
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
    }

    #table-posts {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    #table-posts th,
    #table-posts td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    #table-posts th {
        background: #2c3e50;
        color: white;
    }

    #table-posts tr:hover {
        background: #f0f0f0;
    }
</style>