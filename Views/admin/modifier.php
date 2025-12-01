<section>
    <?php if (!isset($article)): return;
    endif; ?>
    <h2>Modifier l'Article: <?= htmlspecialchars($article->titre) ?></h2>

    <form action="/post/update/<?= $article->id ?>" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" required
                value="<?= htmlspecialchars($_POST['titre'] ?? $article->titre ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" required rows="10">
                <?= htmlspecialchars($_POST['contenu'] ?? $article->contenu ?? '') ?>
            </textarea>
        </div>

        <?php if (!empty($article->media_path)): ?>
            <div class="current-media-display">
                <p>Média actuel : <a href="/uploads/<?= htmlspecialchars($article->media_path) ?>" target="_blank">Voir</a></p>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="media">Remplacer Média (Laisser vide pour garder l'ancien) :</label>
            <input type="file" id="media" name="media" accept="image/*,video/mp4">
        </div>

        <button type="submit" class="btn-submit">Mettre à Jour l'Article</button>
    </form>
</section>