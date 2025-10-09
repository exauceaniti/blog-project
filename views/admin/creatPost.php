<?php
session_start();
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';

$connexion = new Connexion();
$postController = new PostController($connexion);

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->create();
}
error_log("DEBUG: Auteur ID = " . ($_SESSION['user_id'] ?? 'NULL'));

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer un article</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>
    <h1>Créer un nouvel article</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="titre">Titre :</label>
        <input type="hidden" name="from_admin" value="1">

        <input type="text" name="titre" id="titre" required>

        <label for="contenu">Contenu :</label>
        <textarea name="contenu" id="contenu" rows="8" required></textarea>

        <label for="media">Media (optionnel) :</label>
        <input type="file" name="media" id="media" accept="image/*,video/*">

        <button type="submit">Publier</button>
    </form>

    <a href="index.php?route=admin/dashboard">← Retour au dashboard</a>
</body>

</html>