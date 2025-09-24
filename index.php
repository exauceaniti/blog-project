<?php
// index.php - Page d'accueil moderne
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir les variables pour le header
$pageTitle = "Accueil - MonBlog";
$additionalCSS = "/assets/css/index.css";

require_once 'config/connexion.php';
require_once 'models/Post.php';

// Connexion à la base de données
$connexion = new Connexion();
$postManager = new Post($connexion);
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
    <div class="hero-visual">
        <div class="floating-elements">
            <div class="floating-element element-1">📚</div>
            <div class="floating-element element-2">✍️</div>
            <div class="floating-element element-3">🌟</div>
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
                            <?php if (!empty($article['media_path'])): ?>
                                <?php if ($article['media_type'] === 'image'): ?>
                                    <img src="/<?php echo htmlspecialchars($article['media_path']); ?>"
                                        alt="<?php echo htmlspecialchars($article['titre']); ?>"
                                        class="article-image">
                                    </img>

                                <?php elseif ($article['media_type'] === 'video'): ?>
                                    <div class="video-container">
                                        <video class="article-video" controls>
                                            <source src="/assets/uploads/<?php echo htmlspecialchars($article['media_path']); ?>" type="video/mp4">
                                        </video>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Placeholder avec couleur aléatoire -->
                                <?php
                                $colors = ['#2563eb', '#7c3aed', '#dc2626', '#16a34a', '#ea580c'];
                                $random_color = $colors[array_rand($colors)];
                                ?>
                                <div class="article-placeholder" style="background: linear-gradient(135deg, <?php echo $random_color; ?>, #00000050);">
                                    <span class="placeholder-icon">📄</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">Article</span>
                                <span class="article-date"><?= date('d/m/Y', strtotime($article['date_publication'])); ?></span>
                            </div>

                            <h3 class="article-title">
                                <a href="/views/article.php?id=<?php echo $article['id']; ?>">
                                    <?php echo htmlspecialchars($article['titre']); ?>
                                </a>
                            </h3>

                            <p class="article-excerpt">
                                <?php
                                $contenu = strip_tags($article['contenu']);
                                echo htmlspecialchars(mb_substr($contenu, 0, 120)) . '...';
                                ?>
                            </p>

                            <div class="article-footer">
                                <span class="article-author">
                                    Par <?= isset($article['auteur']) ? htmlspecialchars($article['auteur']) : "Auteur inconnu"; ?>
                                </span>

                                <a href="views/article.php?id=<?php echo $article['id']; ?>" class="read-more">
                                    Lire la suite →
                                </a>

                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="view-all-container">
                <a href="/index.php?action=articles" class="btn btn-outline">Voir tous les articles</a>
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