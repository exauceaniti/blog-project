<?php
// admin/dashboard.php
session_start();
require_once "../config/connexion.php";
require_once "../models/Post.php";

// 🔹 Vérifier que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

// 🔹 Créer la connexion
$connexion = new Connexion();
$postManager = new Post($connexion);

// 🔹 Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'ajouter':
            $titre = trim($_POST['titre']);
            $contenu = trim($_POST['contenu']);
            if ($titre && $contenu) {
                $postManager->ajouterArticle($titre, $contenu, $_SESSION['user_id'], $_FILES['media'] ?? null);
            }
            break;

        case 'modifier':
            $id = (int)$_POST['id'];
            $titre = trim($_POST['titre']);
            $contenu = trim($_POST['contenu']);
            if ($id && $titre && $contenu) {
                $postManager->modifierArticle($id, $titre, $contenu, $_FILES['media'] ?? null);
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        nav a {
            margin: 0 15px;
            color: #ecf0f1;
            text-decoration: none;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Cards stats */
        .stats-container {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1;
            min-width: 150px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .card p {
            color: #7f8c8d;
            font-weight: 500;
        }

        /* Sections */
        .section-title {
            color: #2c3e50;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #3498db;
        }

        /* Forms */
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ecf0f1;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-warning {
            background: #f39c12;
            color: white;
        }

        .btn-warning:hover {
            background: #d35400;
        }

        /* Articles list */
        .articles-grid {
            display: grid;
            gap: 1.5rem;
        }

        .article-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3498db;
        }

        .article-header {
            display: flex;
            justify-content: between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .article-title {
            color: #2c3e50;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            flex: 1;
        }

        .article-date {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .article-content {
            color: #34495e;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .article-media {
            margin: 1rem 0;
            text-align: center;
        }

        .article-media img,
        .article-media video {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .article-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #ecf0f1;
        }

        .edit-form {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
            display: none;
        }

        .edit-form.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .stats-container {
                flex-direction: column;
            }

            nav a {
                display: block;
                margin: 5px 0;
            }

            .article-actions {
                flex-direction: column;
            }

            .article-actions form {
                width: 100%;
            }

            .article-actions .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>Dashboard Admin</h1>
        <nav>
            <a href="dashboard.php">Accueil</a>
            <a href="manage_posts.php">Gestion Articles</a>
            <a href="manage_users.php">Gestion Utilisateurs</a>
            <a href="../index.php">Accueil public</a>
            <a href="../logout.php">Déconnexion</a>
        </nav>
    </header>

    <div class="container">
        <!-- Section Statistiques -->
        <h2 class="section-title">Tableau de Bord</h2>
        <div class="stats-container">
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

        <!-- Section Ajout d'Article -->
        <h2 class="section-title">Ajouter un Nouvel Article</h2>
        <div class="form-container">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="ajouter">

                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" id="titre" name="titre" placeholder="Entrez le titre de l'article" required>
                </div>

                <div class="form-group">
                    <label for="contenu">Contenu de l'article</label>
                    <textarea id="contenu" name="contenu" placeholder="Rédigez votre article ici..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="media">Image ou Vidéo (optionnel)</label>
                    <input type="file" id="media" name="media" accept="image/*,video/*">
                    <small>Formats supportés: JPG, PNG, GIF, WebP, MP4, AVI, MOV (Max: 10MB)</small>
                </div>

                <button type="submit" class="btn btn-primary">Publier l'Article</button>
            </form>
        </div>

        <!-- Section Liste des Articles -->
        <h2 class="section-title">Gestion des Articles (<?= count($articles) ?>)</h2>
        <div class="articles-grid">
            <?php if (empty($articles)): ?>
                <div class="article-card">
                    <p style="text-align: center; color: #7f8c8d;">Aucun article publié pour le moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="article-card">
                        <div class="article-header">
                            <div style="flex: 1;">
                                <h3 class="article-title"><?= htmlspecialchars($article['titre']) ?></h3>
                                <div class="article-date">
                                    Par <?= htmlspecialchars($article['auteur']) ?> •
                                    <?= date('d/m/Y à H:i', strtotime($article['date_publication'])) ?>
                                </div>
                            </div>
                        </div>

                        <div class="article-content">
                            <?= nl2br(htmlspecialchars($article['contenu'])) ?>
                        </div>

                        <!-- Affichage du Média -->
                        <?php if (!empty($article['media_path'])): ?>
                            <div class="article-media">
                                <?php if ($article['media_type'] === 'image'): ?>
                                    <img src="../<?= $article['media_path'] ?>"
                                        alt="Image de l'article"
                                        onerror="this.style.display='none'">
                                <?php elseif ($article['media_type'] === 'video'): ?>
                                    <video controls>
                                        <source src="../<?= $article['media_path'] ?>" type="video/mp4">
                                        Votre navigateur ne supporte pas la lecture vidéo.
                                    </video>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="article-actions">
                            <!-- Bouton Modifier -->
                            <button type="button" class="btn btn-warning"
                                onclick="toggleEditForm(<?= $article['id'] ?>)">
                                Modifier
                            </button>

                            <!-- Formulaire Supprimer -->
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.')">
                                    Supprimer
                                </button>
                            </form>
                        </div>

                        <!-- Formulaire de Modification (caché par défaut) -->
                        <div id="edit-form-<?= $article['id'] ?>" class="edit-form">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="modifier">
                                <input type="hidden" name="id" value="<?= $article['id'] ?>">

                                <div class="form-group">
                                    <label>Titre</label>
                                    <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Contenu</label>
                                    <textarea name="contenu" required><?= htmlspecialchars($article['contenu']) ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Nouvelle image/vidéo (optionnel)</label>
                                    <input type="file" name="media" accept="image/*,video/*">
                                    <small>Laisser vide pour conserver le média actuel</small>
                                </div>

                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn"
                                        onclick="toggleEditForm(<?= $article['id'] ?>)">
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Fonction pour afficher/masquer le formulaire de modification
        function toggleEditForm(articleId) {
            const form = document.getElementById('edit-form-' + articleId);
            form.classList.toggle('active');
        }

        // Masquer tous les formulaires de modification au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const editForms = document.querySelectorAll('.edit-form');
            editForms.forEach(form => form.classList.remove('active'));
        });
    </script>
</body>

</html>