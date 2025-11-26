<h1>Bienvenue sur Exau-Blog</h1>
<p class="subtitle">Découvrez nos derniers articles :</p>

<?php if (!empty($articles_list)): ?>
    <div class="articles-grid">
        <?php foreach ($articles_list as $article): ?>
            <div class="article-card">
                <h2><?= htmlspecialchars($article->titre) ?></h2>

                <div class="article-content">
                    <p><?= substr(htmlspecialchars($article->contenu), 0, 150) ?>...</p>
                </div>

                <!-- Média si présent -->
                <?php if (!empty($article->media_path)): ?>
                    <div class="article-media">
                        <?php if ($article->media_type === 'image'): ?>
                            <img src="/uploads/<?= htmlspecialchars($article->media_path) ?>"
                                alt="Illustration de l'article">
                        <?php elseif ($article->media_type === 'video'): ?>
                            <video controls>
                                <source src="/uploads/<?= htmlspecialchars($article->media_path) ?>" type="video/mp4">
                                Votre navigateur ne supporte pas la vidéo.
                            </video>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Infos supplémentaires -->
                <div class="article-meta">
                    Publié le <?= date('d/m/Y H:i', strtotime($article->date_publication)) ?>
                    | Auteur #<?= $article->auteur_id ?>
                </div>

                <!-- Nombre de commentaires -->
                <div class="article-comments">
                    💬 <?= $article->comment_count ?? 0 ?> commentaire(s)
                </div>

                <!-- Bouton Voir plus -->
                <div class="article-actions">
                    <a href="/articles/<?= $article->id ?>" class="btn-view">
                        Voir plus →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-articles">
        <p>Aucun article disponible pour le moment.</p>
    </div>
<?php endif; ?>