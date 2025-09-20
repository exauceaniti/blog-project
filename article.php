<?php
session_start();
require_once 'includes/header.php';
require_once 'classes/connexion.php';
require_once 'classes/Post.php';
require_once 'classes/commentaire.php';

// Connexion à la base de données
$connexion = new Connexion();
$postManager = new Post($connexion);
$commentaireManager = new commentaire($connexion);


//Je verifie si l'id est bien un entier
$articles = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Récupération de l'ID de l'article depuis l'URL
$articleId = $_GET['id'] ?? null;
$article = $postManager->getArticleById($articleId);

if (!$article) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Récupération des commentaires pour cet article
$commentaires = $commentaireManager->voirCommentaires($articleId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['titre']); ?></title>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <article class="post">
            <h1><?php echo htmlspecialchars($article['titre']); ?></h1>
            <p class="post-meta">Publié par <?= htmlspecialchars($article['auteur']); ?> le <?= date('d/m/Y', strtotime($article['date_publication'])); ?></p>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
            </div>
        </article>

        <section class="comments">
            <h2>Commentaires</h2>
            <?php if (empty($commentaires)): ?>
                <p>Aucun commentaire pour cet article.</p>
            <?php else: ?>
                <?php foreach ($commentaires as $comment): ?>
                    <div class="comment">
                        <p><strong><?= htmlspecialchars($comment['auteur']); ?></strong> le <?= date('d/m/Y H:i', strtotime($comment['date_Commentaire'])); ?></p>
                        <p><?= nl2br(htmlspecialchars($comment['contenu'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="handlers/commentaire_handlers.php" method="POST">
                    <input type="hidden" name="action" value="ajouter">
                    <input type="hidden" name="articleId" value="<?php echo $articleId; ?>">
                    <textarea name="contenu" required placeholder="Votre commentaire..."></textarea>
                    <button type="submit">Ajouter un commentaire</button>
                </form>
            <?php else: ?>
                <p>Vous devez être connecté pour ajouter un commentaire. <a href="login.php">Se connecter</a></p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>