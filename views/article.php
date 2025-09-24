<?php
session_start();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/commentaire.php';

// Connexion à la base de données
$connexion = new Connexion();
$postManager = new Post($connexion);
$commentaireManager = new commentaire($connexion);

// Vérification si l'id est bien un entier
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupération de l'article
$article = $postManager->getArticleById($articleId);

if (!$article) {
    header('HTTP/1.0 404 Not Found');
    include __DIR__ . '/404.php';
    exit;
}

// Récupération des commentaires pour cet article
$commentaires = $commentaireManager->voirCommentaires($articleId);

// Traitement de l'ajout de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter' && isset($_SESSION['user_id'])) {
    $contenu = trim($_POST['contenu']);
    $articleIdPost = $_POST['articleId'] ?? null;

    if (!empty($contenu) && $articleIdPost) {
        $success = $commentaireManager->ajouterCommentaire($contenu, $articleIdPost, $_SESSION['user_id']);

        if ($success) {
            header("Location: article.php?id=$articleIdPost");
            exit;
        } else {
            echo '<p style="color:red;">Erreur lors de l\'ajout du commentaire.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre'] ?? 'Article'); ?></title>
    <link rel="stylesheet" href="/assets/css/article.css">

</head>

<body>
    <main>
        <article class="post">
            <h1 class="post-title"><?= htmlspecialchars($article['titre'] ?? ''); ?></h1>
            <div class="post-meta">
                <span class="post-author"><?= htmlspecialchars($article['auteur'] ?? 'Inconnu'); ?></span>
                <span class="post-date"><?= date('d/m/Y à H:i', strtotime($article['date_publication'] ?? 'now')); ?></span>
            </div>

            <!-- Bloc média -->
            <?php if (!empty($article['media_path']) && !empty($article['media_type'])): ?>
                <div class="post-media">
                    <?php if ($article['media_type'] === 'image'): ?>
                        <img src="/<?php echo htmlspecialchars($article['media_path']); ?>"
                            alt="<?php echo htmlspecialchars($article['titre']); ?>"
                            class="article-image">
                        </img>
                    <?php elseif ($article['media_type'] === 'video'): ?>
                        <video controls>
                            <source src="/assets/uploads/<?php echo htmlspecialchars($article['media_path']); ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de cette vidéo.
                        </video>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="post-content">
                <?= nl2br(htmlspecialchars($article['contenu'] ?? '')); ?>
            </div>
        </article>

        <!-- Commentaires -->
        <section class="comments-section">
            <h2 class="comments-title">Commentaires</h2>

            <?php if (empty($commentaires)): ?>
                <p>Aucun commentaire pour cet article !</p>
            <?php else: ?>
                <?php foreach ($commentaires as $comment): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <span class="comment-author"><?= htmlspecialchars($comment['auteur'] ?? 'Anonyme'); ?></span>
                            <span class="comment-date"><?= date('d/m/Y à H:i', strtotime($comment['date_Commentaire'] ?? 'now')); ?></span>
                        </div>
                        <div class="comment-content"><?= nl2br(htmlspecialchars($comment['contenu'] ?? '')); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="add-comment-container">
                <button id="add-comment-btn" class="btn btn-primary">Ajouter un commentaire</button>
                <div id="comment-form-container" style="display:none;">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="" method="POST" class="comment-form">
                            <input type="hidden" name="action" value="ajouter">
                            <input type="hidden" name="articleId" value="<?= $articleId; ?>">
                            <textarea name="contenu" required placeholder="Partagez votre pensée..."></textarea>
                            <button type="submit" class="btn">Publier le commentaire</button>
                        </form>
                    <?php else: ?>
                        <p>Vous devez être connecté pour ajouter un commentaire.</p>
                        <a href="/views/login.php" class="btn btn-login">Se connecter</a>
                        <a href="/views/register.php" class="btn btn-register">Créer un compte</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('add-comment-btn');
            const form = document.getElementById('comment-form-container');

            btn.addEventListener('click', function() {
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                    btn.textContent = 'Masquer le formulaire';
                } else {
                    form.style.display = 'none';
                    btn.textContent = 'Ajouter un commentaire';
                }
            });
        });
    </script>

</body>

</html>