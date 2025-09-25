<?php
// admin/dashboard.php
session_start();

require_once "../views/includes/header.php";
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
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article ajouté avec succès!'];
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
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article modifié avec succès!'];
            }
            break;

        case 'supprimer':
            $id = (int)$_POST['id'];
            if ($id) {
                $postManager->supprimerArticle($id);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article supprimé avec succès!'];
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

// Récupérer le toast s'il existe
$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - GraphicArt Style</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Header Admin -->
    <header class="admin-header">
        <div class="header-content">
            <div class="admin-title">
                <i class="fas fa-tachometer-alt"></i>
                <h1>Dashboard Admin</h1>
            </div>
            <div class="admin-actions">
                <span>Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="../views/logout.php" class="btn btn-secondary">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Toast Notification -->
        <?php if ($toast): ?>
            <div class="toast <?= $toast['type'] === 'error' ? 'error' : '' ?>" id="toast">
                <i class="fas fa-<?= $toast['type'] === 'error' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                <?= htmlspecialchars($toast['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Section Statistiques -->
        <h2 class="section-title">
            <i class="fas fa-chart-bar"></i>
            Tableau de Bord
        </h2>
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-newspaper fa-2x" style="color: var(--accent-1); margin-bottom: 0.5rem;"></i>
                <h2><?= $nbArticles ?></h2>
                <p>Articles Publiés</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users fa-2x" style="color: var(--accent-2); margin-bottom: 0.5rem;"></i>
                <h2><?= $nbUsers ?></h2>
                <p>Utilisateurs Inscrits</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-comments fa-2x" style="color: var(--accent-3); margin-bottom: 0.5rem;"></i>
                <h2><?= $nbCommentaires ?></h2>
                <p>Commentaires</p>
            </div>
        </div>

        <!-- Section Ajout d'Article -->
        <h2 class="section-title">
            <i class="fas fa-plus-circle"></i>
            Ajouter un Nouvel Article
        </h2>
        <div class="form-container">
            <form method="POST" action="" enctype="multipart/form-data" id="article-form">
                <input type="hidden" name="action" value="ajouter">

                <div class="form-group">
                    <label for="titre">
                        <i class="fas fa-heading"></i> Titre de l'article
                    </label>
                    <input type="text" id="titre" name="titre" placeholder="Entrez un titre accrocheur..." required>
                </div>

                <div class="form-group">
                    <label for="contenu">
                        <i class="fas fa-edit"></i> Contenu de l'article
                    </label>
                    <textarea id="contenu" name="contenu" placeholder="Rédigez votre contenu ici..." required></textarea>
                    <small style="color: var(--text-secondary);">
                        <i class="fas fa-info-circle"></i> Supporte le formatage basique
                    </small>
                </div>

                <div class="form-group">
                    <label for="media">
                        <i class="fas fa-file-upload"></i> Média (image/vidéo/audio)
                    </label>
                    <input type="file" name="media" id="media" accept="image/*,video/*,audio/*"
                        onchange="previewMedia(this)">
                    <small style="color: var(--text-secondary);">
                        <i class="fas fa-info-circle"></i> Formats supportés: JPG, PNG, MP4, MP3, etc.
                    </small>
                </div>

                <div id="media-preview" class="media-preview" style="display: none; margin-top: 1rem;">
                    <div id="preview-content"></div>
                    <button type="button" class="btn btn-secondary" onclick="clearPreview()" style="margin-top: 0.5rem;">
                        <i class="fas fa-times"></i> Supprimer la prévisualisation
                    </button>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Publier l'Article
                </button>
            </form>
        </div>

        <!-- Section Liste des Articles -->
        <h2 class="section-title">
            <i class="fas fa-list-alt"></i>
            Gestion des Articles (<?= count($articles) ?>)
        </h2>
        <div class="articles-grid">
            <?php if (empty($articles)): ?>
                <div class="article-card" style="text-align: center; padding: 3rem;">
                    <i class="fas fa-newspaper fa-3x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-secondary); font-size: 1.1rem;">Aucun article publié pour le moment.</p>
                    <p style="color: var(--text-secondary);">Commencez par créer votre premier article !</p>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="article-card" data-article-id="<?= $article['id'] ?>">
                        <div class="article-header">
                            <div style="flex: 1;">
                                <h3 class="article-title">
                                    <i class="fas fa-file-alt" style="color: var(--primary-color);"></i>
                                    <?= htmlspecialchars($article['titre']) ?>
                                </h3>
                                <div class="article-date">
                                    <i class="fas fa-user"></i> Par <?= htmlspecialchars($article['auteur']) ?> •
                                    <i class="fas fa-calendar"></i> <?= date('d/m/Y à H:i', strtotime($article['date_publication'])) ?>
                                </div>
                            </div>
                            <div class="article-status">
                                <span class="status-badge" style="background: var(--accent-3); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                                    <i class="fas fa-check"></i> Publié
                                </span>
                            </div>
                        </div>

                        <div class="article-content">
                            <?= nl2br(htmlspecialchars(mb_strimwidth($article['contenu'], 0, 300, '...'))) ?>
                        </div>

                        <!-- Affichage du Média avec fonctionnalité de zoom -->
                        <?php if (!empty($article['media_path'])): ?>
                            <div class="article-media">
                                <?php
                                $mediaPath = strpos($article['media_path'], 'assets/uploads/') === 0
                                    ? "../" . $article['media_path']
                                    : "../assets/uploads/" . $article['media_path'];
                                ?>

                                <div class="media-container" onclick="toggleZoom(this)">
                                    <?php if ($article['media_type'] === 'image'): ?>
                                        <img src="<?= htmlspecialchars($mediaPath) ?>"
                                            alt="Image de l'article"
                                            onerror="this.style.display='none'">
                                        <div class="media-overlay">
                                            <i class="fas fa-search-plus"></i> Cliquer pour zoomer
                                        </div>
                                    <?php elseif ($article['media_type'] === 'video'): ?>
                                        <video width="100%" controls>
                                            <source src="<?= htmlspecialchars($mediaPath) ?>" type="video/mp4">
                                            Votre navigateur ne supporte pas la vidéo.
                                        </video>
                                        <div class="media-overlay">
                                            <i class="fas fa-play"></i> Vidéo
                                        </div>
                                    <?php elseif ($article['media_type'] === 'audio'): ?>
                                        <audio controls style="width: 100%">
                                            <source src="<?= htmlspecialchars($mediaPath) ?>" type="audio/mpeg">
                                            Votre navigateur ne supporte pas l'audio.
                                        </audio>
                                        <div class="media-overlay">
                                            <i class="fas fa-music"></i> Audio
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="article-actions">
                            <button type="button" class="btn btn-warning"
                                onclick="toggleEditForm(<?= $article['id'] ?>)">
                                <i class="fas fa-edit"></i> Modifier
                            </button>

                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>

                            <button type="button" class="btn btn-secondary"
                                onclick="toggleArticleContent(<?= $article['id'] ?>)">
                                <i class="fas fa-eye"></i> Voir plus
                            </button>
                        </div>

                        <!-- Formulaire de Modification (caché par défaut) -->
                        <div id="edit-form-<?= $article['id'] ?>" class="edit-form">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="modifier">
                                <input type="hidden" name="id" value="<?= $article['id'] ?>">

                                <div class="form-group">
                                    <label><i class="fas fa-heading"></i> Titre</label>
                                    <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-edit"></i> Contenu</label>
                                    <textarea name="contenu" required style="min-height: 200px;"><?= htmlspecialchars($article['contenu']) ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-file-upload"></i> Nouveau média (optionnel)</label>
                                    <input type="file" name="media" accept="image/*,video/*,audio/*">
                                    <small style="color: var(--text-secondary);">
                                        Laisser vide pour conserver le média actuel
                                    </small>
                                </div>

                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="toggleEditForm(<?= $article['id'] ?>)">
                                        <i class="fas fa-times"></i> Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal pour zoom -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 100%;">
        </div>
    </div>

    <script>
        // Gestion du thème
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Changer l'icône
            const icon = document.querySelector('.theme-toggle i');
            icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        // Appliquer le thème sauvegardé au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);

            const icon = document.querySelector('.theme-toggle i');
            icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

            // Afficher le toast si présent
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }
        });

        // Fonction pour afficher/masquer le formulaire de modification
        function toggleEditForm(articleId) {
            const form = document.getElementById('edit-form-' + articleId);
            form.classList.toggle('active');
        }

        // Fonction pour voir plus/moins de contenu
        function toggleArticleContent(articleId) {
            const articleCard = document.querySelector(`[data-article-id="${articleId}"]`);
            const content = articleCard.querySelector('.article-content');
            const fullContent = articleCard.dataset.fullContent;

            if (!fullContent) {
                // Stocker le contenu complet
                articleCard.dataset.fullContent = content.innerHTML;
                // Récupérer le contenu complet via AJAX ou afficher tout
                content.innerHTML = content.innerHTML.replace('...', '') +
                    '<div style="margin-top: 0.5rem;"><button class="btn btn-secondary" onclick="toggleArticleContent(' + articleId + ')">Voir moins</button></div>';
            } else {
                content.innerHTML = fullContent;
                delete articleCard.dataset.fullContent;
            }
        }

        // Fonctionnalité de zoom pour les images
        function toggleZoom(element) {
            const img = element.querySelector('img');
            if (img) {
                const modal = document.getElementById('modalOverlay');
                const modalImg = document.getElementById('modalImage');

                modalImg.src = img.src;
                modal.classList.add('active');
            }
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
        }

        // Prévisualisation des médias avant upload
        function previewMedia(input) {
            const preview = document.getElementById('media-preview');
            const previewContent = document.getElementById('preview-content');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        previewContent.innerHTML = `<img src="${e.target.result}" style="max-width: 300px; border-radius: 8px;">`;
                    } else if (file.type.startsWith('video/')) {
                        previewContent.innerHTML = `<video controls src="${e.target.result}" style="max-width: 300px;"></video>`;
                    } else if (file.type.startsWith('audio/')) {
                        previewContent.innerHTML = `<audio controls src="${e.target.result}"></audio>`;
                    } else {
                        previewContent.innerHTML = `<p>Fichier: ${file.name}</p>`;
                    }
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }
        }

        function clearPreview() {
            document.getElementById('media-preview').style.display = 'none';
            document.getElementById('media').value = '';
        }

        // Masquer tous les formulaires de modification au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const editForms = document.querySelectorAll('.edit-form');
            editForms.forEach(form => form.classList.remove('active'));
        });

        // Fermer le modal avec ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>

</html>