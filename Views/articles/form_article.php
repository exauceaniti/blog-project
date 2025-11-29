<?php

/**
 * views/fragments/form_article.php
 * Formulaire polyvalent pour l'ajout (si $article est null) ou la modification (si $article est défini).
 */

// Définir les variables pour le formulaire
$isEdit = isset($article) && is_object($article);
$formAction = $isEdit ? "/post/update/{$article->id}" : "/post/create";
$submitLabel = $isEdit ? "Mettre à Jour l'Article" : "Publier l'Article";
$pageTitle = $isEdit ? "Modifier l'Article: " . htmlspecialchars($article->titre) : "✍️ Publier un Nouvel Article";
?>

<section class="article-form-section">
    <h2><?= $pageTitle ?></h2>

    <form action="<?= $formAction ?>" method="POST" enctype="multipart/form-data" class="article-form">

        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" required
                value="<?= htmlspecialchars($article->titre ?? $_POST['titre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" required rows="10">
                <?= htmlspecialchars($article->contenu ?? $_POST['contenu'] ?? '') ?>
            </textarea>
        </div>

        <?php if ($isEdit && !empty($article->media_path)): ?>
            <div class="current-media-display">
                <p>Média actuel :</p>
                <?php if ($article->media_type === 'image'): ?>
                    <img src="/uploads/<?= htmlspecialchars($article->media_path) ?>" alt="Média actuel" style="max-width: 150px;">
                <?php elseif ($article->media_type === 'video'): ?>
                    <video controls src="/uploads/<?= htmlspecialchars($article->media_path) ?>" style="max-width: 150px;"></video>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="media"><?= $isEdit ? 'Remplacer Média' : 'Image ou Vidéo' ?> (Max 5Mo) :</label>
            <input type="file" id="media" name="media" accept="image/*,video/mp4">
        </div>

        <?php if (!$isEdit): ?>
            <input type="hidden" name="auteur_id" value="<?= $_SESSION['user_id'] ?? 1 ?>">
        <?php endif; ?>

        <button type="submit" class="btn-submit"><?= $submitLabel ?></button>
    </form>
</section>