<?php
//index.php page d'accueil pour tout ce que les mondes.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/header.php';
require_once 'classes/connexion.php';
require_once 'classes/Post.php';

// Connexion à la base de données
$connexion = new Connexion();
$postManager = new Post($connexion);

$articles = $postManager->voirArticles();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil de mon blog</title>
</head>

<body>
    <main>
        <h1> Articles les plus recents </h1>
        <?php if (empty($articles)): ?>
            <p>Aucun article disponible.</p>

        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <article class="post">
                    <h2><a href="article.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['titre']); ?></a></h2>
                    <p class="post-meta">Publie par <?= htmlspecialchars($article['auteur']); ?> le <?= date('d/m/Y', strtotime($article['date_publication'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars(mb_substr($article['contenu'], 0, 200))); ?>...</p>
                    <a href="article.php?id=<?php echo $article['id']; ?>">Lire la suite</a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>