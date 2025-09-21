<?php
// admin/manage_posts.php
session_start();
// cette classe gere la page d'administration des articlses
//Elles gere tout ce qui cadre avec les articles voir : ajout, modifications, suppression
require_once '../classes/connexion.php';
require_once '../classes/article.php';
require_once '../classes/Post.php';

//connexion a la base de donnees
$connexion = new Connexion();
$post = new Post($connexion);
$conn = $connexion->connecter(); // on recupere l'objet PDO


// Ajouter un article
if (isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $auteurId = $_SESSION['user_id']; // ID de l'auteur, supposé stocké en session

    $post->ajouterArticle($titre, $contenu, $auteurId);
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

//lire les articles
$articles = $post->voirArticles();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Gestion des Articles</h1>

    <h2>Ajouter un Article</h2>
    <form action="manage_posts.php" method="POST">
        <input type="text" name="titre" placeholder="Titre" required>
        <textarea name="contenu" placeholder="Contenu" required></textarea>
        <input type="hidden" name="ajouter" value="1">
        <button type="submit">Ajouter</button>
    </form>

    <h2>Modifier un Article</h2>
    <form action="manage_posts.php" method="POST">
        <input type="number" name="id" placeholder="ID de l'article" required>
        <input type="text" name="titre" placeholder="Nouveau Titre" required>
        <textarea name="contenu" placeholder="Nouveau Contenu" required></textarea>
        <input type="hidden" name="modifier" value="1">
        <button type="submit">Modifier</button>
    </form>

    <h2>Supprimer un Article</h2>
    <form action="manage_posts.php" method="POST">
        <input type="number" name="id" placeholder="ID de l'article" required>
        <input type="hidden" name="supprimer" value="1">
        <button type="submit">Supprimer</button>
    </form>

    <h2>Liste des Articles</h2>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <h3><?php echo htmlspecialchars($article['titre']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($article['contenu'])); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>