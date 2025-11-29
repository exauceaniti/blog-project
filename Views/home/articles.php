<?php

/**
 * views/article/list.php
 * Liste complète de tous les articles.
 * Reçoit $articles_list du PostController::index().
 */
$articles_list = $articles_list ?? [];
?>

<h2>📚 La Bibliothèque Complète des Articles</h2>

<?php if (!empty($articles_list)): ?>
    <div class="articles-grid">
        <?php
        // Boucle sur TOUS les articles et inclut le fragment
        foreach ($articles_list as $article):
            require __DIR__ . '/../../templates/includes/article_card.php';
        endforeach;
        ?>
    </div>
<?php else: ?>
    <div class="no-articles">
        <p>Aucun article n'a été publié pour le moment.</p>
    </div>
<?php endif; ?>