<?php
session_start();
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/controllers/PostController.php';

// Connexion à la BDD
$connexion = new Connexion();

// Instanciation du contrôleur
$controller = new PostController($connexion);

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Récupérer les articles et pagination
$articles = $controller->getArticlesForPage($page); // on va créer cette méthode
$totalArticles = $controller->getTotalArticles();
$totalPages = ceil($totalArticles / 10);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Blog - Accueil</title>
    <!-- ton CSS ici -->
</head>

<body>

    <h1>Liste des articles</h1>

    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
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