<?php
/**
 * Vue : Liste des articles
 * ------------------------
 * Paramètres injectés :
 * - $articles_list : tableau d'objets Post (DAO → Service → Controller)
 *
 * Chaque Post contient :
 * - id
 * - titre
 * - contenu
 * - auteur_id
 * - date_publication
 * - media_path (optionnel)
 * - media_type (image|video)
 */
?>

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
                <p>
                    <?= substr(htmlspecialchars($article->contenu), 0, 200) ?>...
                </p>

                <!-- Média si présent -->
                <?php if (!empty($article->media_path)): ?>
                    <div class="article-media">
                        <?php if ($article->media_type === 'image'): ?>
                            <img src="/uploads/<?= htmlspecialchars($article->media_path) ?>"
                                 alt="Illustration de l'article"
                                 style="max-width:300px; height:auto;">
                        <?php elseif ($article->media_type === 'video'): ?>
                            <video controls style="max-width:400px;">
                                <source src="/uploads/<?= htmlspecialchars($article->media_path) ?>" type="video/mp4">
                                Votre navigateur ne supporte pas la vidéo.
                            </video>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Infos supplémentaires -->
                <small>
                    Publié le <?= date('d/m/Y H:i', strtotime($article->date_publication)) ?>
                    | Auteur #<?= $article->auteur_id ?>
                </small>
            </article>
            <hr>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Aucun article disponible pour le moment.</p>
<?php endif; ?>
