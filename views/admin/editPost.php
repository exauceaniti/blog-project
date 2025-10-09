<?php
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';

session_start(); // 🔒 Nécessaire pour les messages et la session admin

$connexion = new Connexion();
$postController = new PostController($connexion);

// Vérification de l'ID dans l'URL
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<h3>⚠️ ID d'article invalide ou manquant.</h3>";
    exit;
}

// Récupération de l'article existant
$article = $postController->getArticleById($id);

if (!$article) {
    echo "<h3>⚠️ Article introuvable.</h3>";
    exit;
}

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->update($id);
    exit; // Redirection déjà gérée dans le contrôleur
}

// Messages de session (erreur ou succès)
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un article - Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <style>
        /* 🎨 Style intégré pour la démo, tu peux le déplacer dans admin.css */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            niko width: 70%;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: 600;
            margin-top: 10px;
            display: block;
            color: #444;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        textarea {
            height: 200px;
            resize: vertical;
        }

        input[type="file"] {
            margin-top: 8px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error {
            background-color: #fdecea;
            color: #b71c1c;
        }

        .success {
            background-color: #e7f9ed;
            color: #1b5e20;
        }

        .current-media {
            margin-top: 10px;
        }

        .current-media img {
            max-width: 180px;
            border-radius: 8px;
            display: block;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
        }

        .back-link:hover {
            color: #000;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>✏️ Modifier l’article</h1>

        <!-- ✅ Messages -->
        <?php if (!empty($errors)): ?>
            <div class="message error">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- 📝 Formulaire -->
        <form method="POST" enctype="multipart/form-data">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>

            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" required><?= htmlspecialchars($article['contenu']) ?></textarea>

            <?php if (!empty($article['media_path'])): ?>
                <div class="current-media">
                    <p>Média actuel :</p>
                    <img src="<?= htmlspecialchars($article['media_path']) ?>" alt="Image actuelle">
                </div>
            <?php endif; ?>

            <label for="media">Changer le média :</label>
            <input type="file" id="media" name="media">

            <button type="submit">💾 Mettre à jour</button>
        </form>

        <a href="index.php?route=admin/manage_posts" class="back-link">⬅ Retour à la liste des articles</a>
    </div>

</body>

</html>