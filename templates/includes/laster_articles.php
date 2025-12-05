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
<style>
    .latest-articles-section {
        margin: 40px 0;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .latest-articles-section h2 {
        font-size: 2em;
        margin-bottom: 10px;
    }

    .latest-articles-section .subtitle {
        color: #666666;
        margin-bottom: 20px;
    }

    .carousel-container {
        position: relative;
    }

    .articles-carousel {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 16px;
        padding-bottom: 10px;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: #ffffff;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 50%;
    }

    .prev-btn {
        left: -20px;
    }

    .next-btn {
        right: -20px;
    }

    .carousel-btn:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }
</style>