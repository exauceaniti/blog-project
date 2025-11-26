<h1>Nos Articles</h1>

<?php if (!empty($articles_list)): ?>
    <div class="articles">
        <?php foreach ($articles_list as $article): ?>
            <article class="article-card">
                <h2>
                    <a href="/articles/<?= $article->id ?>">
                        <?= htmlspecialchars($article->titre) ?>
                    </a>
                </h2>

                <!-- Aperçu du contenu -->
                <p class="article-content">
                    <?= substr(htmlspecialchars($article->contenu), 0, 200) ?>...
                </p>

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
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-articles">
        <p>Aucun article disponible pour le moment.</p>
    </div>
<?php endif; ?>