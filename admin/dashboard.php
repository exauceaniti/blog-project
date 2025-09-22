<?php
session_start();
require_once "../classes/connexion.php";
require_once "../classes/Post.php";

// 🔹 Vérifier que l’utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// 🔹 Créer la connexion
$connexion = new Connexion();
$pdo = $connexion->connecter(); // Retourne un objet PDO
$postManager = new Post($connexion); // Ta classe Post attend Connexion, pas PDO

// 🔹 Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'ajouter':
            $titre = trim($_POST['titre']);
            $contenu = trim($_POST['contenu']);
            if ($titre && $contenu) {
                $postManager->ajouterArticle($titre, $contenu, $_SESSION['user_id']);
            }
            break;

        case 'modifier':
            $id = (int)$_POST['id'];
            $titre = trim($_POST['titre']);
            $contenu = trim($_POST['contenu']);
            if ($id && $titre && $contenu) {
                $postManager->modifierArticle($id, $titre, $contenu);
            }
            break;

        case 'supprimer':
            $id = (int)$_POST['id'];
            if ($id) {
                $postManager->supprimerArticle($id);
            }
            break;
    }

    // Recharger la page après action
    header("Location: dashboard.php");
    exit;
}

// 🔹 Récupérer stats rapides et articles
$nbArticles = $connexion->executerRequete("SELECT COUNT(*) FROM articles")->fetchColumn();
$nbUsers = $connexion->executerRequete("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$nbCommentaires = $connexion->executerRequete("SELECT COUNT(*) FROM commentaires")->fetchColumn();
$articles = $postManager->voirArticles();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        header {
            background: #2c3e50;
            color: white;
            padding: 1rem;
        }

        nav a {
            margin: 0 15px;
            color: #ecf0f1;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 2rem;
        }

        .card {
            display: inline-block;
            background: white;
            padding: 1.5rem;
            margin: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            width: 150px;
            text-align: center;
        }

        h2.section-title {
            margin-top: 2rem;
            color: #2c3e50;
        }

        form {
            margin-bottom: 1.5rem;
        }

        form input[type="text"],
        form textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 0.5rem 1rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background: #2c3e50;
        }

        .article-card {
            border: 1px solid #ddd;
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .article-actions form {
            display: inline-block;
        }

        .article-actions button {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Dashboard Admin</h1>
        <nav>
            <a href="dashboard.php">Accueil</a>
            <a href="../logout.php">Déconnexion</a>
            <a href="../index.php">Accueil public</a>
        </nav>
    </header>

    <div class="container">
        <h2 class="section-title">Résumé rapide</h2>
        <div class="card">
            <h2><?= $nbArticles ?></h2>
            <p>Articles</p>
        </div>
        <div class="card">
            <h2><?= $nbUsers ?></h2>
            <p>Utilisateurs</p>
        </div>
        <div class="card">
            <h2><?= $nbCommentaires ?></h2>
            <p>Commentaires</p>
        </div>

        <h2 class="section-title">Gestion des Articles</h2>

        <!-- Formulaire ajout article -->
        <form method="POST" action="">
            <input type="hidden" name="action" value="ajouter">
            <input type="text" name="titre" placeholder="Titre de l'article" required>
            <textarea name="contenu" placeholder="Contenu de l'article" required></textarea>
            <button type="submit">Ajouter Article</button>
        </form>

        <!-- Liste des articles existants -->
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <h3><?= htmlspecialchars($article['titre']); ?></h3>
                <p><?= nl2br(htmlspecialchars($article['contenu'])); ?></p>
                <div class="article-actions">
                    <!-- Modifier -->
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="modifier">
                        <input type="hidden" name="id" value="<?= $article['id']; ?>">
                        <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']); ?>" required>
                        <textarea name="contenu" required><?= htmlspecialchars($article['contenu']); ?></textarea>
                        <button type="submit">Modifier</button>
                    </form>
                    <!-- Supprimer -->
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id" value="<?= $article['id']; ?>">
                        <button type="submit" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');">Supprimer</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</body>

</html>