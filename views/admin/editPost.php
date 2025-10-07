<?php
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';

$connexion = new Connexion();
$postController = new PostController($connexion);

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID de l'article manquant.";
    exit;
}

$article = $postController->postModel->voirArticle($id);
if (!$article) {
    echo "Article introuvable.";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->update($id);
}
?>

<h1>Modifier l'article</h1>

<form method="POST" enctype="multipart/form-data">
    <label>Titre :</label>
    <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>

    <label>Contenu :</label>
    <textarea name="contenu" required><?= htmlspecialchars($article['contenu']) ?></textarea>

    <label>Media :</label>
    <input type="file" name="media">

    <button type="submit">Mettre à jour</button>
</form>