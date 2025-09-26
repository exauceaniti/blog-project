<?php
// index.php - Page d'accueil moderne
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir les variables pour le header
$pageTitle = "Accueil - MonBlog";
$additionalCSS = "/assets/css/index.css";
$additionalJS = "/assets/js/animations.js";

require_once 'config/connexion.php';
require_once 'models/Post.php';
require_once 'views/includes/functions.php'; // <-- Ajouter le helper media_url()

// Connexion à la base de données
$connexion = new Connexion();
$pdo = $connexion->connecter();

// Récupérer les articles récents (limite à 6 pour la page d'accueil)
$articlesRecents = $pdo->query("
    SELECT a.*, u.nom AS auteur
    FROM articles a
    JOIN utilisateurs u ON a.auteur_id = u.id
    ORDER BY a.date_publication DESC
    LIMIT 6
")->fetchAll();

// Inclure le header
require_once 'views/includes/header.php';
?>

<!-- Section Hero -->
<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Bienvenue sur MonBlog</h1>
        <p class="hero-description">Découvrez des articles passionnants sur divers sujets. Partagez vos idées et connectez-vous avec une communauté de lecteurs curieux.</p>
        <div class="hero-buttons">
            <a href="#articles" class="btn btn-primary">Découvrir les articles</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/views/register.php" class="btn btn-secondary">Créer un compte</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Section Articles Récents -->
<section id="articles" class="recent-articles">
    <div class="container">
        <h2 class="section-title">Articles Récents</h2>

        <?php if (empty($articlesRecents)): ?>
            <div class="no-posts">
                <p>Aucun article disponible pour le moment.</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/admin/manage_posts.php" class="btn btn-primary">Créer le premier article</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="articles-grid">

                <?php foreach ($articlesRecents as $article): ?>
                    <article class="article-card">
                        <div class="article-media">
                            <!-- Dans votre boucle d'articles, remplacez la section media par : -->
                            <div class="article-media">
                                <?php if (!empty($article['media_path'])): ?>
                                    <?php
                                    $mediaUrl = strpos($article['media_path'], 'assets/uploads/') === 0
                                        ? "/" . $article['media_path']
                                        : "/assets/uploads/" . $article['media_path'];
                                    ?>

                                    <?php if ($article['media_type'] === 'image'): ?>
                                        <img src="<?= htmlspecialchars($mediaUrl) ?>"
                                            alt="<?= htmlspecialchars($article['titre']) ?>"
                                            class="article-image"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">

                                    <?php elseif ($article['media_type'] === 'video'): ?>
                                        <div class="video-container">
                                            <video controls>
                                                <video class="article-video"
                                                    controls
                                                    preload="metadata"
                                                    poster="/assets/video-placeholder.jpg">
                                                    <source src="<?= htmlspecialchars($mediaUrl) ?>#t=0.1" type="video/mp4">
                                                    Votre navigateur ne supporte pas la lecture vidéo.
                                                </video>
                                            </video>

                                        </div>

                                    <?php elseif ($article['media_type'] === 'audio'): ?>
                                        <div class="audio-container">
                                            <audio controls preload="none">
                                                <source src="<?= htmlspecialchars($mediaUrl) ?>" type="audio/mpeg">
                                                Votre navigateur ne supporte pas la lecture audio.
                                            </audio>
                                        </div>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <div class="article-placeholder"> 😂 </div>
                                <?php endif; ?>
                            </div>

                            <div class="article-content">
                                <div class="article-meta">
                                    <span class="article-category">Article</span>
                                    <span class="article-date"><?= date('d/m/Y', strtotime($article['date_publication'])); ?></span>
                                </div>

                                <h3 class="article-title">
                                    <a href="/views/article.php?id=<?= $article['id'] ?>">
                                        <?= htmlspecialchars($article['titre']) ?>
                                    </a>
                                </h3>

                                <p class="article-excerpt">
                                    <?= htmlspecialchars(mb_substr(strip_tags($article['contenu']), 0, 120)) ?>...
                                </p>

                                <div class="article-footer">
                                    <span class="article-author">
                                        Par <?= isset($article['auteur']) ? htmlspecialchars($article['auteur']) : "Auteur inconnu"; ?>
                                    </span>
                                    <a href="/views/article.php?id=<?= $article['id'] ?>" class="read-more">Lire la suite →</a>
                                </div>
                            </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="view-all-container">
                <a href="/views/article.php" class="btn btn-outline">Voir tous les articles</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Section Statistiques -->
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?= count($articlesRecents); ?>+</div>
                <div class="stat-label">Articles publiés</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Contenu disponible</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Gratuit</div>
            </div>
        </div>
    </div>
</section>

<?php
// Inclure le footer
require_once 'views/includes/footer.php';
?>