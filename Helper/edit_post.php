<section id="add-post">
    <h3>✏️ Modifier l'article</h3>
    <form action="index.php?route=admin/update_post&id=<?= $article['id'] ?>" method="POST"
        enctype="multipart/form-data">
        <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
        <textarea name="contenu" rows="5" required><?= htmlspecialchars($article['contenu']) ?></textarea>
        <input type="file" name="media">
        <button type="submit">💾 Mettre à jour</button>
    </form>
</section>