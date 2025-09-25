<?php
// admin/dashboard.php
session_start();

require_once "../config/connexion.php";
require_once "../models/Post.php";

// Vérifier que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

// Connexion & manager
$connexion = new Connexion();
$postManager = new Post($connexion);

// Gestion POST (ajouter / modifier / supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'ajouter':
                $titre = trim($_POST['titre'] ?? '');
                $contenu = trim($_POST['contenu'] ?? '');
                if ($titre && $contenu) {
                    $postManager->ajouterArticle(
                        $titre,
                        $contenu,
                        $_SESSION['user_id'],
                        $_FILES['media'] ?? null
                    );
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article ajouté avec succès !'];
                } else {
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Titre et contenu requis.'];
                }
                break;

            case 'modifier':
                $id = (int)($_POST['id'] ?? 0);
                $titre = trim($_POST['titre'] ?? '');
                $contenu = trim($_POST['contenu'] ?? '');
                if ($id && $titre && $contenu) {
                    $postManager->modifierArticle($id, $titre, $contenu, $_FILES['media'] ?? null);
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article modifié !'];
                } else {
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Données de modification invalides.'];
                }
                break;

            case 'supprimer':
                $id = (int)($_POST['id'] ?? 0);
                if ($id) {
                    $postManager->supprimerArticle($id);
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article supprimé.'];
                }
                break;
        }
    } catch (Exception $e) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Erreur serveur : ' . $e->getMessage()];
    }

    header("Location: dashboard.php");
    exit;
}

// Récupérer données
$nbArticles = (int)$connexion->executerRequete("SELECT COUNT(*) FROM articles")->fetchColumn();
$nbUsers = (int)$connexion->executerRequete("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$nbCommentaires = (int)$connexion->executerRequete("SELECT COUNT(*) FROM commentaires")->fetchColumn();
$articles = $postManager->voirArticles();

// Toast
$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin • Dashboard</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-brand">
                <a href="dashboard.php">
                    <img src="/assets/uploads/1758785033_df11f7361b.png" alt="logo" class="sidebar-logo">
                    <span>GraphicArt Admin</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                <a href="manage_posts.php"><i class="fas fa-newspaper"></i> Articles</a>
                <a href="manage_users.php"><i class="fas fa-users"></i> Utilisateurs</a>
                <a href="#"><i class="fas fa-comments"></i> Commentaires</a>
                <a href="../index.php"><i class="fas fa-home"></i> Voir le site</a>
                <a href="../views/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </nav>

            <div class="sidebar-footer">
                <button id="sidebarToggle" class="btn-icon" title="Réduire le menu"><i class="fas fa-angle-double-left"></i></button>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <h1>Tableau de bord</h1>
                </div>

                <div class="topbar-right">
                    <div class="user-welcome">Bienvenue, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong></div>
                    <button class="theme-toggle btn-icon" id="themeToggle" title="Changer le thème"><i class="fas fa-moon"></i></button>
                </div>
            </header>

            <!-- Toast -->
            <?php if ($toast): ?>
                <div id="toast" class="toast <?= $toast['type'] === 'error' ? 'error' : 'success' ?>">
                    <i class="fas fa-<?= $toast['type'] === 'error' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                    <span><?= htmlspecialchars($toast['message']) ?></span>
                </div>
            <?php endif; ?>

            <!-- STATS -->
            <section class="stats-grid">
                <div class="stat">
                    <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
                    <div class="stat-body">
                        <div class="stat-number"><?= $nbArticles ?></div>
                        <div class="stat-label">Articles</div>
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-body">
                        <div class="stat-number"><?= $nbUsers ?></div>
                        <div class="stat-label">Utilisateurs</div>
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-icon"><i class="fas fa-comments"></i></div>
                    <div class="stat-body">
                        <div class="stat-number"><?= $nbCommentaires ?></div>
                        <div class="stat-label">Commentaires</div>
                    </div>
                </div>
            </section>

            <!-- ADD ARTICLE FORM (collapsible) -->
            <section class="card">
                <div class="card-header">
                    <h2><i class="fas fa-plus-circle"></i> Ajouter un article</h2>
                    <button class="btn-outline" id="toggleAddForm">Afficher / Masquer</button>
                </div>

                <div class="card-body" id="addForm" style="display:none;">
                    <form id="articleForm" method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="ajouter">
                        <div class="form-row">
                            <label>Titre</label>
                            <input type="text" name="titre" required placeholder="Titre de l'article">
                        </div>

                        <div class="form-row">
                            <label>Contenu</label>
                            <textarea name="contenu" rows="6" required placeholder="Contenu..."></textarea>
                        </div>

                        <div class="form-row">
                            <label>Média (image/vidéo/audio)</label>
                            <input type="file" name="media" id="mediaInput" accept="image/*,video/*,audio/*" onchange="previewMedia(this)">
                            <small class="muted">Formats: jpg, png, webp, mp4, mp3...</small>

                            <div id="mediaPreview" class="media-preview" style="display:none;">
                                <div id="previewInner"></div>
                                <button type="button" class="btn-outline" onclick="clearPreview()">Supprimer la prévisualisation</button>
                            </div>
                        </div>

                        <div class="form-row" style="display:flex; gap:.5rem;">
                            <button type="submit" class="btn-primary"><i class="fas fa-paper-plane"></i> Publier</button>
                            <button type="reset" class="btn" onclick="clearPreview()">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- ARTICLES LIST -->
            <section class="card">
                <div class="card-header">
                    <h2><i class="fas fa-list-alt"></i> Articles (<?= count($articles) ?>)</h2>
                    <div class="card-actions">
                        <input type="search" id="searchInput" placeholder="Rechercher un titre..." oninput="filterArticles(this.value)">
                    </div>
                </div>

                <div class="card-body articles-grid" id="articlesGrid">
                    <?php if (empty($articles)): ?>
                        <div class="empty">Aucun article publié pour le moment.</div>
                    <?php else: ?>
                        <?php foreach ($articles as $article): ?>
                            <div class="article-card" data-title="<?= htmlspecialchars(strtolower($article['titre'])) ?>" data-article-id="<?= $article['id'] ?>">
                                <div class="article-top">
                                    <div class="article-meta">
                                        <h3><?= htmlspecialchars($article['titre']) ?></h3>
                                        <div class="meta-sub">Par <?= htmlspecialchars($article['auteur']) ?> • <?= date('d/m/Y', strtotime($article['date_publication'])) ?></div>
                                    </div>

                                    <!-- status badge -->
                                    <div class="article-badge"><i class="fas fa-check"></i> Publié</div>
                                </div>

                                <div class="article-body">
                                    <div class="article-excerpt"><?= nl2br(htmlspecialchars(mb_strimwidth($article['contenu'], 0, 250, '...'))) ?></div>

                                    <?php if (!empty($article['media_path'])): ?>
                                        <?php
                                        // Construire chemin robuste (admin file is in admin/)
                                        if (strpos($article['media_path'], 'assets/uploads/') === 0) {
                                            $mediaPath = "../" . $article['media_path']; // already relative path in DB
                                        } else {
                                            $mediaPath = "../assets/uploads/" . $article['media_path'];
                                        }
                                        ?>
                                        <div class="article-media">
                                            <div class="media-thumb" onclick="toggleZoom('<?= htmlspecialchars($mediaPath, ENT_QUOTES) ?>')">
                                                <?php if ($article['media_type'] === 'image'): ?>
                                                    <img src="<?= htmlspecialchars($mediaPath) ?>" alt="media" onerror="this.style.display='none'">
                                                <?php elseif ($article['media_type'] === 'video'): ?>
                                                    <video src="<?= htmlspecialchars($mediaPath) ?>" muted loop playsinline></video>
                                                <?php elseif ($article['media_type'] === 'audio'): ?>
                                                    <div class="audio-placeholder"><i class="fas fa-music"></i></div>
                                                <?php endif; ?>

                                                <div class="media-overlay"><i class="fas fa-search-plus"></i></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="article-actions">
                                    <button class="btn-warning" onclick="toggleEditForm(<?= $article['id'] ?>)" type="button"><i class="fas fa-edit"></i> Modifier</button>

                                    <form method="POST" action="" onsubmit="return confirm('Supprimer cet article ?');" style="display:inline;">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                        <button class="btn-danger" type="submit"><i class="fas fa-trash"></i> Supprimer</button>
                                    </form>

                                    <button class="btn" type="button" onclick="toggleArticleContent(<?= $article['id'] ?>)"><i class="fas fa-eye"></i> Voir plus</button>
                                </div>

                                <!-- EDIT FORM (hidden) -->
                                <div class="edit-form" id="edit-form-<?= $article['id'] ?>">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="modifier">
                                        <input type="hidden" name="id" value="<?= $article['id'] ?>">

                                        <div class="form-row">
                                            <label>Titre</label>
                                            <input type="text" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
                                        </div>

                                        <div class="form-row">
                                            <label>Contenu</label>
                                            <textarea name="contenu" rows="6" required><?= htmlspecialchars($article['contenu']) ?></textarea>
                                        </div>

                                        <div class="form-row">
                                            <label>Nouveau média (optionnel)</label>
                                            <input type="file" name="media" accept="image/*,video/*,audio/*">
                                            <small class="muted">Laisser vide pour conserver l'ancien média</small>
                                        </div>

                                        <div style="display:flex; gap:.5rem; margin-top:.5rem;">
                                            <button class="btn-primary" type="submit"><i class="fas fa-save"></i> Enregistrer</button>
                                            <button class="btn" type="button" onclick="toggleEditForm(<?= $article['id'] ?>)"><i class="fas fa-times"></i> Annuler</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <footer class="admin-footer">
                <small>&copy; <?= date('Y') ?> GraphicArt • Dashboard</small>
            </footer>
        </main>
    </div>

    <!-- Modal zoom -->
    <div id="modalOverlay" class="modal-overlay" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            <div id="modalInner"></div>
        </div>
    </div>

    <script src="/assets/js/admin.js"></script>
</body>

</html>