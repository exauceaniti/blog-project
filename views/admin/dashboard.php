<?php
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';

$connexion = new Connexion();
$postController = new PostController($connexion);

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5;

$articles = $postController->getArticlesForPage($page, $limit);
$totalPages = $postController->getTotalPages($limit);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Articles</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 250px;
            background-color: #1e293b;
            color: #fff;
            min-height: 100vh;
            padding: 20px;
        }

        .sidebar h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            padding: 10px 0;
            border-bottom: 1px solid #334155;
        }

        .sidebar a:hover {
            background-color: #334155;
            color: #fff;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        h1 {
            margin-top: 0;
        }

        .article-card {
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .article-card img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .article-card h2 {
            margin-top: 0;
        }

        .article-card a {
            color: #7c3aed;
            text-decoration: none;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 3px;
            background-color: #7c3aed;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #6d28d9;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="index.php?route=admin/dashboard">Articles</a>
        <a href="index.php?route=admin/manage_users">Utilisateurs</a>
        <a href="index.php?route=admin/manage_comments">Commentaires</a>
        <a href="index.php?route=admin/logout">Déconnexion</a>
    </div>

    <div class="main-content">
        <h1>Articles</h1>
        <a href="index.php?route=admin/create_post">➕ Ajouter un nouvel article</a>

        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-card">
                    <?php if (!empty($article['media_path'])): ?>
                        <img src="<?= htmlspecialchars($article['media_path']) ?>" alt="Media article">
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($article['titre']) ?></h2>
                    <p><?= substr(htmlspecialchars($article['contenu']), 0, 150) ?>...</p>
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($article['auteur_nom'] ?? 'Inconnu') ?></p>
                    <a href="index.php?route=admin/edit_post&id=<?= $article['id'] ?>">Modifier</a> |
                    <a href="index.php?route=admin/delete_post&id=<?= $article['id'] ?>"
                        onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                </div>
            <?php endforeach; ?>

            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?route=admin/dashboard&page=<?= $i ?>"
                        class="<?= ($i === $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>

        <?php else: ?>
            <p>Aucun article disponible pour le moment.</p>
        <?php endif; ?>
    </div>

</body>

</html>