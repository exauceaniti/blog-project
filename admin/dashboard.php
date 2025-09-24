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
                $postManager->ajouterArticle(
                    $titre,
                    $contenu,
                    $_SESSION['user_id'],
                    $_FILES['media'] ?? null
                );
            }
            break;

        case 'modifier':
            $id = (int)$_POST['id'];
            $titre = trim($_POST['titre']);
            $contenu = trim($_POST['contenu']);
            if ($id && $titre && $contenu) {
                $postManager->modifierArticle(
                    $id,
                    $titre,
                    $contenu,
                    $_FILES['media'] ?? null
                );
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
    <link rel="stylesheet" href="/assets/css/dashboard.css">
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
                    <label for="media">Média (image/vidéo)</label>
                    <input type="file" name="media" accept="image/*,video/*">
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
                                        width="400"
                                        onerror="this.style.display='none'">
                                <?php elseif ($article['media_type'] === 'video'): ?>
                                    <video width="400" controls>
                                        <source src="../<?= $article['media_path'] ?>" type="video/mp4">
                                        Votre navigateur ne supporte pas la vidéo.
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