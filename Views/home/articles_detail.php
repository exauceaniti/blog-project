<?php

/**
 * views/home/articles_detail.php
 * Affiche l'article complet et ses commentaires.
 * * Variables attendues :
 * - $article : Entity\Post
 * - $comments : array d'Entity\Comment
 * - $is_logged_in : bool (devrait venir du Controller en utilisant Authentification::isLoggedIn())
 */
?>

<section class="article-details-section">
    <article class="article-full-view">
        <header class="article-header">
            <h1 class="article-title"><?= htmlspecialchars($article->titre) ?></h1>
            <div class="article-meta">
                Publié le <strong><?= date('d/m/Y H:i', strtotime($article->date_publication)) ?></strong>
                | Par <strong><?= htmlspecialchars($article->auteur_nom) ?></strong>
            </div>
        </header>

        <?php if (!empty($article->media_path)): ?>
            <div class="article-media-full">
                <?php if ($article->media_type === 'image'): ?>
                    <img src="/public/uploads/<?= htmlspecialchars($article->media_path) ?>"
                        alt="Média de l'article : <?= htmlspecialchars($article->titre) ?>"
                        loading="lazy">
                <?php elseif ($article->media_type === 'video'): ?>
                    <video controls preload="metadata" width="100%" poster="/public/uploads/<?= htmlspecialchars(pathinfo($article->media_path, PATHINFO_FILENAME)) ?>_thumb.jpg">
                        <source src="/uploads/<?= htmlspecialchars($article->media_path) ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la vidéo.
                    </video>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="article-body">
            <?= nl2br(htmlspecialchars($article->contenu)) ?>
        </div>
    </article>
</section>

<section class="comments-section">
    <h2>Commentaires : <?= count($comments) ?></h2>

    <?php if ($is_logged_in): ?>
        <div class="comment-form-container">
            <h3>✍️ Laisser un commentaire</h3>
            <form action="/comments/add" method="POST" class="comment-form" id="commentForm">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($article->id) ?>">
                <div class="form-group">
                    <label for="contenu_commentaire">Votre message :</label>
                    <textarea id="contenu_commentaire"
                        name="contenu_commentaire"
                        required
                        rows="5"
                        placeholder="Partagez vos pensées, posez des questions ou ajoutez vos insights..."
                        maxlength="1000"><?= htmlspecialchars($_POST['contenu_commentaire'] ?? '') ?></textarea>
                    <small class="char-count">0/1000 caractères</small>
                </div>

                <button type="submit" class="btn-submit-comment">
                    <span>Envoyer le commentaire</span>
                </button>
            </form>
        </div>
    <?php else: ?>
        <p class="login-prompt">
            🔒 Vous devez être <a href="/login">connecté</a> pour participer à la discussion.
        </p>
    <?php endif; ?>

    <div class="comments-list">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item" id="comment-<?= $comment->id ?>">
                    <div class="comment-header">
                        <span class="comment-author">
                            <?= htmlspecialchars($comment->auteur_nom) ?>
                        </span>
                        <span class="comment-date">
                            📅 Le <?= date('d/m/Y à H:i', strtotime($comment->date_creation)) ?>
                        </span>
                    </div>
                    <p class="comment-body">
                        <?= nl2br(htmlspecialchars($comment->contenu)) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-comments">🎯 Soyez le premier à commenter cet article !</p>
        <?php endif; ?>
    </div>
</section>