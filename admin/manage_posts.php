<?php
// admin/manage_posts.php
session_start();

// Inclusion des fichiers nécessaires
require_once '../config/connexion.php';
require_once '../views/article.php';
require_once '../models/Post.php';

// Connexion à la base de données
$connexion = new Connexion();
$post = new Post($connexion);
$conn = $connexion->connecter(); // objet PDO

// Ajouter un article
if (isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $auteurId = $_SESSION['user_id'];

    // 🔹 Récupérer le fichier uploadé (s'il existe)
    $fichier = $_FILES['media'] ?? null;

    $post->ajouterArticle($titre, $contenu, $auteurId, $fichier);
    header("Location: manage_posts.php");
    exit;
}

// Modifier un article
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];

    $post->modifierArticle($id, $titre, $contenu);
    header("Location: manage_posts.php");
    exit;
}

// Supprimer un article
if (isset($_POST['supprimer'])) {
    $id = $_POST['id'];
    $post->supprimerArticle($id);
    header("Location: manage_posts.php");
    exit;
}

// Lire les articles
$articles = $post->voirArticles();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles</title>
</head>

<body>
    <h1>Gestion des Articles</h1>

    <!-- Formulaire d'ajout -->
    <h2>Ajouter un Article</h2>
    <form action="manage_posts.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre" required><br><br>
        <textarea name="contenu" placeholder="Contenu" required></textarea><br><br>
        <input type="file" name="media" accept="image/*,video/*"><br><br>
        <input type="hidden" name="ajouter" value="1">
        <button type="submit">Ajouter</button>
    </form>

    <!-- Formulaire de modification -->
    <h2>Modifier un Article</h2>
    <form action="manage_posts.php" method="POST">
        <input type="number" name="id" placeholder="ID de l'article" required><br><br>
        <input type="text" name="titre" placeholder="Nouveau Titre" required><br><br>
        <textarea name="contenu" placeholder="Nouveau Contenu" required></textarea><br><br>
        <input type="hidden" name="modifier" value="1">
        <button type="submit">Modifier</button>
    </form>

    <!-- Formulaire de suppression -->
    <h2>Supprimer un Article</h2>
    <form action="manage_posts.php" method="POST">
        <input type="number" name="id" placeholder="ID de l'article" required><br><br>
        <input type="hidden" name="supprimer" value="1">
        <button type="submit">Supprimer</button>
    </form>

    <!-- Liste des articles -->
    <h2>Liste des Articles</h2>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <h3><?php echo htmlspecialchars($article['titre']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($article['contenu'])); ?></p>

                <!-- Affichage du média -->
                <?php if ($article['media_type'] === 'image'): ?>
                    <img src="../<?php echo htmlspecialchars($article['media_path']); ?>"
                        alt="Image de l'article" width="250">
                <?php elseif ($article['media_type'] === 'video'): ?>
                    <video width="320" height="240" controls>
                        <source src="../<?php echo htmlspecialchars($article['media_path']); ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture vidéo.
                    </video>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>