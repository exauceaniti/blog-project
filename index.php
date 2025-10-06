<?php
session_start();
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/controllers/PostController.php';
require_once __DIR__ . '/views/includes/header.php';

// Connexion à la BDD
$connexion = new Connexion();
$controller = new PostController($connexion);

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Articles et pagination
$articles = $controller->getArticlesForPage($page);
$totalArticles = $controller->getTotalArticles();
$totalPages = ceil($totalArticles / 10);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Blog - Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f8f8f8;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
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
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .article-card h2 {
            margin-top: 0;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            background: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background: #0056b3;
        }

        .pagination a:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <h1>Liste des articles</h1>

    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <?php if (!empty($article['media_path'])): ?>
                    <img src="<?= htmlspecialchars($article['media_path']) ?>" alt="Image article">
                <?php endif; ?>
                <h2><?= htmlspecialchars($article['titre']) ?></h2>
                <p><?= substr(htmlspecialchars($article['contenu']), 0, 150) ?>...</p>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($article['auteur_nom'] ?? 'Inconnu') ?></p>
                <a href="show.php?id=<?= $article['id'] ?>">Lire plus</a>
            </div>
        <?php endforeach; ?>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <p>Aucun article disponible pour le moment.</p>
    <?php endif; ?>

</body>

</html>