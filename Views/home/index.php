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
 * - comment_count (injecté par le Controller)
 */
?>

<h1>Bienvenue sur Mon Blog</h1>
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


<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
        background-color: #f5f5f5;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    h1 {
        text-align: center;
        margin-bottom: 15px;
        color: #333;
        font-weight: 600;
    }
    
    .subtitle {
        text-align: center;
        margin-bottom: 30px;
        color: #666;
        font-size: 18px;
    }
    
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .article-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }
    
    .article-card h2 {
        margin-bottom: 12px;
        color: #333;
        font-size: 20px;
        line-height: 1.3;
    }
    
    .article-content {
        color: #555;
        line-height: 1.5;
        margin-bottom: 15px;
        flex-grow: 1;
    }
    
    .article-media {
        margin: 15px 0;
        text-align: center;
    }
    
    .article-media img,
    .article-media video {
        max-width: 100%;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .article-meta {
        color: #888;
        font-size: 14px;
        margin-bottom: 10px;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }

    .article-comments {
        font-size: 14px;
        color: #4a90e2;
        margin-bottom: 15px;
    }
    
    .article-actions {
        margin-top: auto;
    }
    
    .btn-view {
        display: inline-block;
        padding: 10px 20px;
        background: #4a90e2;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s;
        text-align: center;
        width: 100%;
    }
    
    .btn-view:hover {
        background: #3a7bc8;
    }
    
    .no-articles {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 8px;
        color: #666;
        font-size: 18px;
    }
    
    @media (max-width: 768px) {
        .articles-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        body {
            padding: 15px;
        }
    }
</style>
