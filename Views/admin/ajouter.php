<section>
    <h2>✍️ Publier un Nouvel Article</h2>

    <form action="/post/create" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" required
                value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" required rows="10">
                <?= htmlspecialchars($_POST['contenu'] ?? '') ?>
            </textarea>
        </div>

        <div class="form-group">
            <label for="media">Image ou Vidéo (Max 5Mo) :</label>
            <input type="file" id="media" name="media" accept="image/*,video/mp4" required>
        </div>

        <input type="hidden" name="auteur_id" value="<?= $_SESSION['user_id'] ?? 1 ?>">

        <button type="submit" class="btn-submit">Publier l'Article</button>
    </form>
</section>