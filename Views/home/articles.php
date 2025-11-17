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
            max-width: 1000px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 15px;
        }
        
        .articles {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        .article-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .article-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        .article-card h2 {
            margin-bottom: 15px;
        }
        
        .article-card h2 a {
            color: #333;
            text-decoration: none;
            font-size: 24px;
            transition: color 0.3s;
        }
        
        .article-card h2 a:hover {
            color: #4a90e2;
        }
        
        .article-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .article-media {
            margin: 20px 0;
            text-align: center;
        }
        
        .article-media img,
        .article-media video {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .article-meta {
            color: #888;
            font-size: 14px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .no-articles {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            color: #666;
            font-size: 18px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        hr {
            display: none; /* On retire les hr puisque les cartes sont déjà séparées */
        }
        
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .article-card {
                padding: 20px;
            }
            
            .article-card h2 a {
                font-size: 20px;
            }
        }
    </style>