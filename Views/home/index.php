<?php

/**
 * views/home/index.php
 * Page d'accueil principale.
 * Reçoit $latest_articles_list du PostController::home().
 */

// Assurez-vous que $latest_articles_list est défini
$articles_list = $latest_articles_list ?? [];
?>

<section class="hero-section">
    <h1>Bienvenue sur Exau-Blog : Le Savoir à Portée de Main.</h1>
    <p>Votre source d'information et d'inspiration sur le développement web, la technologie et bien plus.</p>
    <a href="/articles" class="btn-primary">Voir tous les Articles</a>
</section>

<section class="latest-articles-section">
    <h2>🔥 Nos 5 derniers articles</h2>
    <p class="subtitle">Ne manquez pas les nouveautés !</p>

    <?php if (!empty($articles_list)): ?>
        <div class="carousel-container">
            <div class="articles-carousel" id="latest-articles-carousel">
                <?php
                // Boucle sur les 5 articles et inclut le fragment
                foreach ($articles_list as $article):
                    $card_params = ['article' => $article];
                    \Src\Core\Render\Fragment::articleCard($card_params);
                endforeach;
                ?>
            </div>
            <button class="carousel-btn prev-btn">←</button>
            <button class="carousel-btn next-btn">→</button>
        </div>
    <?php else: ?>
        <p class="no-articles">Aucun article récent n'est encore disponible.</p>
    <?php endif; ?>
</section>

<section class="ambitions-section">
    <h2>💡 Nos Ambitions</h2>
    <p>Nous sommes dédiés à éduquer, inspirer et connecter la communauté tech.</p>
</section>

<script src="/js/carousel.js"></script>