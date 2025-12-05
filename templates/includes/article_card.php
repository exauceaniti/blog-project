<?php

/**
 * views/fragments/article_card.php
 * Affiche un article unique (Doit recevoir $article comme Entité Post).
 */

if (!isset($article) || !is_object($article) || !($article instanceof \Src\Entity\Post)) {
    return;
}
?>

<div class="article-card">
    <h2 class="article-title">
        <a href="/articles/<?= htmlspecialchars($article->id) ?>">
            <?= htmlspecialchars($article->titre) ?>
        </a>
    </h2>

    <?php if (!empty($article->media_path)): ?>
        <div class="article-media">
            <?php if ($article->media_type === 'image'): ?>
                <img src="/public/uploads/<?= htmlspecialchars($article->media_path) ?>"
                    alt="Illustration de l'article : <?= htmlspecialchars($article->titre) ?>">
            <?php elseif ($article->media_type === 'video'): ?>
                <video controls preload="metadata">
                    <source src="/uploads/<?= htmlspecialchars($article->media_path) ?>" type="video/mp4">
                    Votre navigateur ne supporte pas la vidéo.
                </video>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="article-content">
        <p><?= substr(htmlspecialchars($article->contenu), 0, 150) ?>...</p>
    </div>

    <div class="article-meta">
        Publié le <strong> <?= date('d/m/Y H:i', strtotime($article->date_publication)) ?> </strong>
        | Par <strong> <?= htmlspecialchars($article->auteur_nom) ?> </strong>
    </div>

    <div class="article-comments">
        💬 <strong> <?= $article->comment_count ?> commentaire(s) </strong>
    </div>

    <div class="article-actions">
        <a href="/articles/<?= htmlspecialchars($article->id) ?>" class="btn-view">
            Lire l'article complet →
        </a>
    </div>
</div>