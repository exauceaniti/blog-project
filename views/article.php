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
$notification = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    if (isset($_SESSION['user_id'])) {
        $contenu = trim($_POST['contenu']);
        $articleIdPost = $_POST['articleId'] ?? null;

        if (!empty($contenu) && $articleIdPost) {
            $success = $commentaireManager->ajouterCommentaire($contenu, $articleIdPost, $_SESSION['user_id']);

            if ($success) {
                $notification = ['type' => 'success', 'message' => 'Commentaire ajouté avec succès!'];
                // Recharger les commentaires
                $commentaires = $commentaireManager->voirCommentaires($articleId);
            } else {
                $notification = ['type' => 'error', 'message' => 'Erreur lors de l\'ajout du commentaire.'];
            }
        } else {
            $notification = ['type' => 'error', 'message' => 'Le commentaire ne peut pas être vide.'];
        }
    } else {
        $notification = ['type' => 'error', 'message' => 'Vous devez être connecté pour commenter.'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre'] ?? 'Article'); ?> - GraphicArt Blog</title>
    <link rel="stylesheet" href="/assets/css/article.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Bouton thème -->
    <button class="theme-toggle" onclick="toggleTheme()">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Notification -->
    <?php if ($notification): ?>
        <div class="notification <?= $notification['type'] === 'error' ? 'error' : 'show' ?>" id="notification">
            <i class="fas fa-<?= $notification['type'] === 'error' ? 'exclamation-triangle' : 'check-circle' ?>"></i>
            <?= htmlspecialchars($notification['message']) ?>
        </div>
    <?php endif; ?>

    <main>
        <!-- Article principal -->
        <article class="post">
            <h1 class="post-title">
                <i class="fas fa-file-alt"></i>
                <?= htmlspecialchars($article['titre'] ?? ''); ?>
            </h1>

            <div class="post-meta">
                <span class="post-author">
                    <i class="fas fa-user"></i>
                    <?= htmlspecialchars($article['auteur'] ?? 'Inconnu'); ?>
                </span>
                <span class="post-date">
                    <i class="fas fa-calendar"></i>
                    <?= date('d/m/Y à H:i', strtotime($article['date_publication'] ?? 'now')); ?>
                </span>
            </div>

            <!-- Bloc média avec fonctionnalité de zoom -->
            <?php if (!empty($article['media_path']) && !empty($article['media_type'])): ?>
                <div class="post-media">
                    <?php
                    $mediaPath = strpos($article['media_path'], 'assets/uploads/') === 0
                        ? $article['media_path']
                        : '/assets/uploads/' . $article['media_path'];
                    ?>

                    <div class="media-container" onclick="toggleZoom(this)">
                        <?php if ($article['media_type'] === 'image'): ?>
                            <img src="<?= htmlspecialchars($mediaPath); ?>"
                                alt="<?= htmlspecialchars($article['titre']); ?>"
                                class="article-image">
                            <div class="media-overlay">
                                <i class="fas fa-search-plus"></i>
                                Cliquer pour zoomer
                            </div>
                        <?php elseif ($article['media_type'] === 'video'): ?>
                            <video controls class="article-video">
                                <source src="<?= htmlspecialchars($mediaPath); ?>" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de cette vidéo.
                            </video>
                            <div class="media-overlay">
                                <i class="fas fa-play-circle"></i>
                                Vidéo
                            </div>
                        <?php elseif ($article['media_type'] === 'audio'): ?>
                            <audio controls class="article-audio">
                                <source src="<?= htmlspecialchars($mediaPath); ?>" type="audio/mpeg">
                                Votre navigateur ne supporte pas l'audio.
                            </audio>
                            <div class="media-overlay">
                                <i class="fas fa-music"></i>
                                Audio
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="post-content">
                <?= nl2br(htmlspecialchars($article['contenu'] ?? '')); ?>
            </div>

            <!-- Actions de partage -->
            <div class="post-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button class="btn btn-secondary" onclick="shareArticle()">
                        <i class="fas fa-share-alt"></i> Partager
                    </button>
                    <button class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                </div>
            </div>
        </article>

        <!-- Section commentaires -->
        <section class="comments-section">
            <h2 class="comments-title">
                <i class="fas fa-comments"></i>
                Commentaires
                <span class="comment-count" style="background: var(--primary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 20px; font-size: 0.8rem;">
                    <?= count($commentaires) ?>
                </span>
            </h2>

            <?php if (empty($commentaires)): ?>
                <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                    <i class="fas fa-comment-slash fa-3x" style="margin-bottom: 1rem;"></i>
                    <p>Soyez le premier à commenter cet article !</p>
                </div>
            <?php else: ?>
                <div class="comments-list">
                    <?php foreach ($commentaires as $comment): ?>
                        <div class="comment" data-comment-id="<?= $comment['id'] ?>">
                            <div class="comment-header">
                                <span class="comment-author">
                                    <i class="fas fa-user-circle"></i>
                                    <?= htmlspecialchars($comment['auteur'] ?? 'Anonyme'); ?>
                                </span>
                                <span class="comment-date">
                                    <i class="fas fa-clock"></i>
                                    <?= date('d/m/Y à H:i', strtotime($comment['date_Commentaire'] ?? 'now')); ?>
                                </span>
                            </div>
                            <div class="comment-content">
                                <?= nl2br(htmlspecialchars($comment['contenu'] ?? '')); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'ajout de commentaire -->
            <div class="add-comment-container">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button id="add-comment-btn" class="btn btn-primary" type="button" onclick="toggleCommentForm()">
                        <i class="fas fa-plus-circle"></i> Ajouter un commentaire
                    </button>


                    <div id="comment-form-container" style="display:none;">
                        <form action="" method="POST" class="comment-form" id="commentForm">
                            <input type="hidden" name="action" value="ajouter">
                            <input type="hidden" name="articleId" value="<?= $articleId; ?>">

                            <div class="form-group">
                                <label for="contenu" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                                    <i class="fas fa-edit"></i> Votre commentaire
                                </label>
                                <textarea name="contenu" required placeholder="Partagez votre pensée..."
                                    id="contenu" maxlength="500"></textarea>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                    <small style="color: var(--text-secondary);">
                                        <i class="fas fa-info-circle"></i> Maximum 500 caractères
                                    </small>
                                    <span id="charCount" style="color: var(--text-secondary);">0/500</span>
                                </div>
                            </div>

                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Publier le commentaire
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="toggleCommentForm()">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 2rem; background: var(--background-color); border-radius: var(--border-radius);">
                        <i class="fas fa-lock fa-2x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
                        <p style="margin-bottom: 1rem; color: var(--text-secondary);">
                            Vous devez être connecté pour ajouter un commentaire.
                        </p>
                        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                            <a href="/views/login.php" class="btn btn-login">
                                <i class="fas fa-sign-in-alt"></i> Se connecter
                            </a>
                            <a href="/views/register.php" class="btn btn-register">
                                <i class="fas fa-user-plus"></i> Créer un compte
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Modal pour zoom image -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 100%; border-radius: var(--border-radius);">
        </div>
    </div>

    <script>
        // Gestion du thème
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Changer l'icône
            const icon = document.querySelector('.theme-toggle i');
            icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        // Appliquer le thème sauvegardé
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);

            const icon = document.querySelector('.theme-toggle i');
            icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

            // Cacher la notification après 5 secondes
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000);
            }

            // Compteur de caractères
            const textarea = document.getElementById('contenu');
            const charCount = document.getElementById('charCount');

            if (textarea && charCount) {
                textarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length + '/500';
                    if (this.value.length > 450) {
                        charCount.style.color = 'var(--warning)';
                    } else if (this.value.length > 490) {
                        charCount.style.color = 'var(--danger)';
                    } else {
                        charCount.style.color = 'var(--text-secondary)';
                    }
                });
            }
        });

        // Fonctionnalité de zoom pour les images
        function toggleZoom(element, mediaSrc = null) {
            const modal = document.getElementById('modalOverlay');
            const modalImg = document.getElementById('modalImage');

            let img = element.querySelector('img');

            if (mediaSrc) {
                modalImg.src = mediaSrc; // chemin correct passé depuis PHP
            } else if (img) {
                modalImg.src = img.src; // fallback si mediaSrc non fourni
            }

            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('modalOverlay');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('modalImage').src = ''; // reset image
        }


        // Gestion du formulaire de commentaire
        function toggleCommentForm() {
            const btn = document.getElementById('add-comment-btn');
            const form = document.getElementById('comment-form-container');

            if (form.style.display === 'none') {
                form.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-minus-circle"></i> Masquer le formulaire';
                btn.classList.add('bounce');
                setTimeout(() => btn.classList.remove('bounce'), 1000);
            } else {
                form.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-plus-circle"></i> Ajouter un commentaire';
            }
        }

        // Partager l'article
        function shareArticle() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } else {
                // Fallback pour les navigateurs qui ne supportent pas l'API Share
                navigator.clipboard.writeText(window.location.href);
                alert('Lien copié dans le presse-papier !');
            }
        }

        // Fermer le modal avec ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les commentaires pour l'animation
        document.addEventListener('DOMContentLoaded', function() {
            const comments = document.querySelectorAll('.comment');
            comments.forEach(comment => {
                comment.style.opacity = '0';
                comment.style.transform = 'translateY(20px)';
                comment.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(comment);
            });
        });
    </script>
</body>

</html>