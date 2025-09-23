<?php
session_start();
require_once 'views/includes/header.php';
require_once 'config/connexion.php';
require_once 'models/Post.php';
require_once 'models/commentaire.php';

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
    include '404.php';
    exit;
}

// Récupération des commentaires pour cet article
$commentaires = $commentaireManager->voirCommentaires($articleId);

// Traitement de l'ajout de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter' && isset($_SESSION['user_id'])) {
    $contenu = trim($_POST['contenu']);
    $articleId = $_POST['articleId'] ?? null;

    if (!empty($contenu) && $articleId) {
        $success = $commentaireManager->ajouterCommentaire($contenu, $articleId, $_SESSION['user_id']);

        if ($success) {
            header("Location: article.php?id=$articleId");
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
    <title><?php echo htmlspecialchars($article['titre']); ?></title>
    <style>
        /* ... ton style déjà existant reste inchangé ... */

        .post-media {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .post-media img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .post-media video {
            width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
    </style>
</head>

<body>
    <main>
        <article class="post">
            <h1 class="post-title"><?php echo htmlspecialchars($article['titre']); ?></h1>
            <div class="post-meta">
                <span class="post-author"><?= htmlspecialchars($article['auteur']); ?></span>
                <span class="post-date"><?= date('d/m/Y à H:i', strtotime($article['date_publication'])); ?></span>
            </div>

            <!-- Ajout du bloc média -->
            <?php if (!empty($article['media_path'])): ?>
                <div class="post-media">
                    <?php if ($article['media_type'] === 'image'): ?>
                        <img src="uploads/<?php echo htmlspecialchars($article['media_path']); ?>" alt="Image de l'article">
                    <?php elseif ($article['media_type'] === 'video'): ?>
                        <video controls>
                            <source src="uploads/<?php echo htmlspecialchars($article['media_path']); ?>" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de cette vidéo.
                        </video>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
            </div>
        </article>

        <!-- Section Commentaires (inchangée sauf si tu veux design amélioré) -->
        <section class="comments-section">
            <h2 class="comments-title">Commentaires</h2>

            <?php if (empty($commentaires)): ?>
                <div class="no-comments">
                    <p>Aucun commentaire pour cet article !</p>
                </div>
            <?php else: ?>
                <?php foreach ($commentaires as $comment): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <span class="comment-author"><?= htmlspecialchars($comment['auteur']); ?></span>
                            <span class="comment-date"><?= date('d/m/Y à H:i', strtotime($comment['date_Commentaire'])); ?></span>
                        </div>
                        <div class="comment-content">
                            <?= nl2br(htmlspecialchars($comment['contenu'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="add-comment-container">
                <button id="add-comment-btn" class="btn btn-primary">Ajouter un commentaire</button>

                <div id="comment-form-container" style="display: none;">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="comment-form">
                            <h3 class="form-title">Votre commentaire</h3>
                            <form action="" method="POST">
                                <input type="hidden" name="action" value="ajouter">
                                <input type="hidden" name="articleId" value="<?= $articleId ?>">
                                <div class="form-group">
                                    <textarea name="contenu" required placeholder="Partagez votre pensée..."></textarea>
                                </div>
                                <button type="submit" class="btn">Publier le commentaire</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="login-required-message">
                            <p>Vous devez être connecté pour ajouter un commentaire.</p>
                            <div class="auth-buttons">
                                <a href="login.php" class="btn btn-login">Se connecter</a>
                                <a href="register.php" class="btn btn-register">Créer un compte</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addCommentBtn = document.getElementById('add-comment-btn');
            const commentFormContainer = document.getElementById('comment-form-container');

            addCommentBtn.addEventListener('click', function() {
                if (commentFormContainer.style.display === 'none') {
                    commentFormContainer.style.display = 'block';
                    addCommentBtn.textContent = 'Masquer le formulaire';
                } else {
                    commentFormContainer.style.display = 'none';
                    addCommentBtn.textContent = 'Ajouter un commentaire';
                }
            });

            <?php if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                commentFormContainer.style.display = 'block';
                addCommentBtn.textContent = 'Masquer le formulaire';
            <?php endif; ?>
        });
    </script>
</body>

</html>