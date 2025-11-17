<?php
/**
 * Vue : Page d'accueil
 * --------------------
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

<h1>Bienvenue sur Mon Blog</h1>
<p>Découvrez nos derniers articles :</p>

<?php if (!empty($articles_list)): ?>
    <div class="articles-grid">
        <?php foreach ($articles_list as $article): ?>
            <div class="article-card">
                <h2><?= htmlspecialchars($article->titre) ?></h2>

                <!-- Aperçu du contenu -->
                <p><?= substr(htmlspecialchars($article->contenu), 0, 150) ?>...</p>

                <!-- Média si présent -->
                <?php if (!empty($article->media_path)): ?>
                    <div class="article-media">
                        <?php if ($article->media_type === 'image'): ?>
                            <img src="/uploads/<?= htmlspecialchars($article->media_path) ?>"
                                 alt="Illustration"
                                 style="max-width:200px; height:auto;">
                        <?php elseif ($article->media_type === 'video'): ?>
                            <video controls style="max-width:250px;">
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
    <p>Aucun article disponible pour le moment.</p>
<?php endif; ?>

<style>
    .articles-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .article-card {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        width: 300px;
        background: #f9f9f9;
    }
    .article-card h2 {
        margin-top: 0;
    }
    .article-actions {
        margin-top: 10px;
    }
    .btn-view {
        display: inline-block;
        padding: 8px 12px;
        background: #2c3e50;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
    }
    .btn-view:hover {
        background: #34495e;
    }
</style>
