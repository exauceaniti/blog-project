<?php
//index.php page d'accueil pour tout ce que les mondes.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'views/includes/header.php';
require_once 'config/connexion.php';
require_once 'models/Post.php';

// Connexion à la base de données
$connexion = new Connexion();
$postManager = new Post($connexion);

// Récupérer les articles
$articles = $postManager->voirArticles();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil de mon blog</title>
    <link rel="stylesheet" href="public/index.css">
</head>

<body>
    <main>
        <h1 class="page-title">Articles les plus récents</h1>

        <?php if (empty($articles)): ?>
            <div class="no-posts">
                <p>Aucun article disponible pour le moment.</p>
                <a href="create.php" class="create-post-btn">Créer le premier article</a>
            </div>
        <?php else: ?>
            <div class="posts-container">
                <?php foreach ($articles as $article):
                    // Générer une couleur d'image de fond aléatoire pour chaque article
                    $colors = ['#3498db', '#2ecc71', '#9b59b6', '#e67e22', '#e74c3c', '#1abc9c'];
                    $random_color = $colors[array_rand($colors)];
                ?>
                    <article class="post">
                        <div class="post-image" style="background-color: <?php echo $random_color; ?>;">
                            <span class="post-category">Article</span>
                        </div>
                        <div class="post-content">
                            <h2 class="post-title">
                                <a href="article.php?id=<?php echo $article['id']; ?>">
                                    <?php echo htmlspecialchars($article['titre']); ?>
                                </a>
                            </h2>
                            <div class="post-meta">
                                <span class="post-author"><?= htmlspecialchars($article['auteur']); ?></span>
                                <span class="post-date"><?= date('d/m/Y', strtotime($article['date_publication'])); ?></span>
                            </div>
                            <p class="post-excerpt">
                                <?php
                                $contenu = strip_tags($article['contenu']);
                                echo nl2br(htmlspecialchars(mb_substr($contenu, 0, 150)));
                                ?>...
                            </p>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">Lire la suite</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>