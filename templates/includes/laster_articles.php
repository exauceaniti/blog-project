<?php
// On s'attend à recevoir $latest_articles_list
if (empty($latest_articles_list) || !is_array($latest_articles_list)):
?>
    <p>Aucun article récent à afficher.</p>
<?php
    return;
endif;
?>

<section class="latest-articles-section">
    <h2>🔥 Nos 5 derniers articles</h2>
    <p class="subtitle">Ne manquez pas les nouveautés !</p>

    <div class="carousel-container">
        <div class="articles-carousel" id="latest-articles-carousel">
            <?php
            // On boucle sur la liste des 5 derniers articles
            foreach ($latest_articles_list as $article):
                // Inclure le fragment de carte pour chaque article
                require __DIR__ . '/../../templates/includes/article_card.php';
            endforeach;
            ?>
        </div>
        <button class="carousel-btn prev-btn" aria-label="Article précédent">←</button>
        <button class="carousel-btn next-btn" aria-label="Article suivant">→</button>
    </div>
</section>