<?php
session_start();
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/controllers/PostController.php';

$db = new Connexion();

$connexion = $db->connecter();

$controller = new PostController($connexion);

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$controller->index($page);

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
            background: #f9f9f9;
        }

        .article-card {
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .article-card h2 {
            margin: 0 0 10px;
        }

        .article-card p {
            margin: 0 0 10px;
        }

        .pagination a {
            padding: 5px 10px;
            margin: 2px;
            border: 1px solid #ccc;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #333;
            color: #fff;
        }

        .messages {
            margin-bottom: 20px;
        }

        .messages p {
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

    <div class="messages">
        <?php
        // Afficher les messages de succès / erreurs
        if (!empty($_SESSION['success'])) {
            echo "<p class='success'>{$_SESSION['success']}</p>";
            unset($_SESSION['success']);
        }
        if (!empty($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $err) {
                echo "<p class='error'>$err</p>";
            }
            unset($_SESSION['errors']);
        }
        ?>
    </div>

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

        <!-- Pagination -->
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