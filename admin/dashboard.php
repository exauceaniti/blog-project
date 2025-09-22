<?php
session_start();
require_once "../classes/connexion.php";

// Vérifier que l’utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$conn = new Connexion();

// 🔹 Récupérer stats rapides
$nbArticles = $conn->executerRequete("SELECT COUNT(*) FROM articles")->fetchColumn();
$nbUsers = $conn->executerRequete("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$nbCommentaires = $conn->executerRequete("SELECT COUNT(*) FROM commentaires")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
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
        }

        .card h2 {
            margin: 0;
            color: #2c3e50;
        }

        .card p {
            margin: 0.5rem 0 0;
            color: #7f8c8d;
        }
    </style>
</head>

<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="dashboard.php">Accueil</a>
            <a href="manage_posts.php">Gérer Articles</a>
            <a href="manage_users.php">Gérer Utilisateurs</a>
            <a href="../logout.php">Déconnexion</a>
            <a href="index.php">Accueil public</a>
        </nav>
    </header>

    <div class="container">
        <h2>Résumé rapide</h2>
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
    </div>
</body>

</html>