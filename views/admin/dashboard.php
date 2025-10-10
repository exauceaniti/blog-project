<?php
// Dashboard admin
session_start();

require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';
require_once __DIR__ . '/../../models/Commentaire.php';
require_once __DIR__ . '/../../models/User.php'; // si tu as une classe User

$connexion = new Connexion();
$postController = new PostController($connexion);
$commentModel = new Commentaire($connexion);
$userModel = new User($connexion);

// Récupérer les stats
$totalArticles = $postController->getTotalArticles();
$totalComments = $commentModel->countAllComments();
$totalUsers = $userModel->countAllUsers(); // à adapter si méthode différente
?>

<section class="admin-section">
    <h2>🛠 Dashboard Admin</h2>

    <div class="stats">
        <div class="stat-card">
            <h3>Articles</h3>
            <p><?= $totalArticles ?></p>
            <a href="/index.php?route=admin/manage_posts" class="btn-manage">Gérer les articles</a>
        </div>
        <div class="stat-card">
            <h3>Commentaires</h3>
            <p><?= $totalComments ?></p>
            <a href="/index.php?route=admin/manage_comments" class="btn-manage">Gérer les commentaires</a>
        </div>
        <div class="stat-card">
            <h3>Utilisateurs</h3>
            <p><?= $totalUsers ?></p>
            <a href="/index.php?route=admin/manage_users" class="btn-manage">Gérer les utilisateurs</a>
        </div>
    </div>
</section>

<style>
    .admin-section {
        padding: 20px;
    }

    .stats {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .stat-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        flex: 1;
        text-align: center;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .stat-card h3 {
        margin-bottom: 10px;
    }

    .stat-card p {
        font-size: 2em;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .btn-manage {
        display: inline-block;
        background: #3498db;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
    }

    .btn-manage:hover {
        background: #2980b9;
    }
</style>