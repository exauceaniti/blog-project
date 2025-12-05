<?php

/**
 * views/fragments/article_card.php
 * Affiche un article unique (Doit recevoir $article comme Entité Post).
 */

if (!isset($article) || !is_object($article) || !($article instanceof \Src\Entity\Post)) {
    return; // Sécurité si la variable n'est pas passée
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
<style>
    .article-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .article-title {
        font-size: 1.5em;
        margin-bottom: 12px;
    }

    .article-title a {
        text-decoration: none;
        color: #333;
    }

    .article-media img,
    .article-media video {
        max-width: 100%;
        border-radius: 4px;
        margin-bottom: 12px;
    }

    .article-content p {
        font-size: 1em;
        color: #555;
        margin-bottom: 12px;
    }

    .article-meta {
        font-size: 0.9em;
        color: #888;
        margin-bottom: 12px;
    }

    .article-comments {
        font-size: 0.9em;
        color: #888;
        margin-bottom: 12px;
    }

    .article-actions .btn-view {
        display: inline-block;
        padding: 8px 16px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .article-actions .btn-view:hover {
        background-color: #0056b3;
    }
</style>