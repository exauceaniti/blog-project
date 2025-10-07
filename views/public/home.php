<?php
session_start();
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';
require_once __DIR__ . '/../../views/public/includes/header.php';

// Connexion à la BDD
$connexion = new Connexion();
$controller = new PostController($connexion);

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5;

// Récupérer les articles pour la page
$articles = $controller->getArticlesForPage($page, $limit);

// Nombre total de pages pour la pagination
$totalArticles = $controller->getTotalArticles();
$totalPages = (int) ceil($totalArticles / $limit);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Blog - Accueil</title>
    <style>
        :root {
            --primary-color: #7c3aed;
            --primary-hover: #6d28d9;
            --background-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 14px rgba(124, 58, 237, 0.1);

            /* COULEURS GRAPHICART STYLE */
            --accent-1: #ec4899;
            --accent-2: #8b5cf6;
            --accent-3: #10b981;
            --gradient-primary: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);

            /* Espacements */
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --border-radius: 12px;
        }

        [data-theme="dark"] {
            --primary-color: #8b5cf6;
            --primary-hover: #a78bfa;
            --background-color: #0f0f23;
            --surface-color: #1a1a2e;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #2d3748;
            --shadow: 0 4px 20px rgba(139, 92, 246, 0.15);

            /* GRADIENTS SOMBRE ÉLÉGANT */
            --gradient-primary: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: var(--background-color);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .article-card {
            background: var(--surface-color);
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
            background: var(--primary-color);
            color: var(--accent-1);
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background: var(--primary-hover);
        }

        .pagination a:hover {
            background: var(--primary-hover);
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