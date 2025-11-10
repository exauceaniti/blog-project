<h1>Liste des articles</h1>

<?php if (!empty($articles)): ?>
    <?php foreach ($articles as $article): ?>
        <div class="article-card">
            <?php if (!empty($article['media_path'])): ?>
                <img src="<?= htmlspecialchars($article['media_path']) ?>" alt="Image article">
            <?php endif; ?>
            <h2><?= htmlspecialchars($article['titre']) ?></h2>
            <p><?= substr(htmlspecialchars($article['contenu']), 0, 150) ?>...</p>
            <p><strong>Auteur :</strong> <?= htmlspecialchars($article['auteur_nom'] ?? 'Inconnu') ?></p>
            <a href="/article/<?= $article['id']?>-<?= $article['slug']?> " >📎 Lire la suite</a>
        </div>
    <?php endforeach; ?>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
<?php else: ?>
    <p>Aucun article disponible pour le moment.</p>
<?php endif; ?>