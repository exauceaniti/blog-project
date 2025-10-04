<?php
// admin/dashboard.php
session_start();

require_once "../config/connexion.php";
require_once "../models/Post.php";

// ========================= SÉCURITÉ =========================
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

// ========================= INITIALISATION =========================
$connexion = new Connexion();
$postManager = new Post($connexion);

// ========================= TRAITEMENT DES ACTIONS POST =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $toast = ['type' => 'error', 'message' => 'Action invalide !'];

    try {
        switch ($action) {
            case 'ajouter':
                $titre = trim($_POST['titre'] ?? '');
                $contenu = trim($_POST['contenu'] ?? '');
                if ($titre && $contenu) {
                    $postManager->ajouterArticle($titre, $contenu, $_SESSION['user_id'], $_FILES['media'] ?? null);
                    $toast = ['type' => 'success', 'message' => 'Article ajouté avec succès !'];
                } else {
                    $toast['message'] = 'Titre et contenu requis.';
                }
                break;

            case 'modifier':
                $id = (int) ($_POST['id'] ?? 0);
                $titre = trim($_POST['titre'] ?? '');
                $contenu = trim($_POST['contenu'] ?? '');
                if ($id && $titre && $contenu) {
                    $postManager->modifierArticle($id, $titre, $contenu, $_FILES['media'] ?? null);
                    $toast = ['type' => 'success', 'message' => 'Article modifié !'];
                } else {
                    $toast['message'] = 'Données de modification invalides.';
                }
                break;

            case 'supprimer':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id) {
                    $postManager->supprimerArticle($id);
                    $toast = ['type' => 'success', 'message' => 'Article supprimé.'];
                }
                break;
        }
    } catch (Exception $e) {
        $toast = ['type' => 'error', 'message' => 'Erreur serveur : ' . $e->getMessage()];
    }

    $_SESSION['toast'] = $toast;
    header("Location: /admin/dashboard.php");
    exit;
}

// ========================= RÉCUPÉRATION DES DONNÉES =========================
$nbArticles = (int) $connexion->executerRequete("SELECT COUNT(*) FROM articles")->fetchColumn();
$nbUsers = (int) $connexion->executerRequete("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$nbCommentaires = (int) $connexion->executerRequete("SELECT COUNT(*) FROM commentaires")->fetchColumn();
$articles = $postManager->voirArticles();

// Toast
$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);

function sanitizeMediaPath($path)
{
    if (!$path)
        return '';
    return (strpos($path, 'assets/uploads/') === 0) ? "../$path" : "../assets/uploads/$path";
}

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
    <!-- Modal pour l'aperçu complet d'article -->
    <div id="articleModal" class="modal-overlay" style="display:none;">
        <div class="modal-content" id="articleModalContent">
            <!-- Le contenu complet de l'article sera injecté ici -->
            <button class="btn btn-secondary" onclick="closeArticleModal()" style="float:right;">Fermer</button>
        </div>
    </div>
    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-brand">
                <a href="/admin/dashboard.php">
                    <img src="/assets/uploads/1758785033_df11f7361b.png" alt="logo" class="sidebar-logo">
                    <span>GraphicArt Admin</span>
                </a>
            </div>
            <nav class="sidebar-nav">
                <a href="/admin/dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                <a href="/admin/manage_posts.php"><i class="fas fa-newspaper"></i> Articles</a>
                <a href="/admin/manage_users.php"><i class="fas fa-users"></i> Utilisateurs</a>
                <a href="/admin/manage_comments.php"><i class="fas fa-comments"></i> Commentaires</a>
                <a href="../index.php"><i class="fas fa-home"></i> Voir le site</a>
                <a href="../views/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </nav>
            <div class="sidebar-footer">
                <button id="sidebarToggle" class="btn-icon" title="Réduire le menu"><i
                        class="fas fa-angle-double-left"></i></button>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <h1>Tableau de bord</h1>
                </div>
                <div class="topbar-right">
                    <div class="user-welcome">Bienvenue,
                        <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></strong></div>
                    <button class="theme-toggle btn-icon" id="themeToggle" title="Changer le thème"><i
                            class="fas fa-moon"></i></button>
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

            <!-- ADD ARTICLE FORM -->
            <section class="card">
                <div class="card-header">
                    <h2><i class="fas fa-plus-circle"></i> Ajouter un article</h2>
                    <button class="btn-outline" id="toggleAddForm">Afficher / Masquer</button>
                </div>
                <div class="card-body" id="addForm" style="display:none;">
                    <form action="" method="POST" enctype="multipart/form-data" class="article-form">
                        <input type="hidden" name="action" value="ajouter">
                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" id="titre" name="titre" maxlength="255"
                                placeholder="Titre de votre article">
                        </div>
                        <div class="form-group">
                            <label for="contenu">Contenu</label>
                            <textarea id="contenu" name="contenu" rows="6" required
                                placeholder="Écrivez ici..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="media">Ajouter un média</label>
                            <input type="file" id="media" name="media" accept="image/*,video/*,audio/*">
                            <small class="hint">📸 Images | 🎥 Vidéos | 🎵 Audios</small>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Publier</button>
                            <button type="reset" class="btn btn-secondary">Annuler</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- ARTICLES LIST -->

            <section class="card">
                <div class="card-header">
                    <h2><i class="fas fa-list-alt"></i> Articles (<?= count($articles) ?>)</h2>
                    <div class="card-actions">
                        <input type="search" id="searchInput" placeholder="Rechercher un titre..."
                            oninput="filterArticles(this.value)">
                    </div>
                </div>
                <div class="card-body articles-grid" id="articlesGrid">
                    <?php if (empty($articles)): ?>
                        <div class="empty">Aucun article publié pour le moment.</div>
                    <?php else: ?>
                        <?php foreach ($articles as $article):
                            $mediaPath = sanitizeMediaPath($article['media_path']);
                            ?>
                            <div class="article-card" data-title="<?= htmlspecialchars(strtolower($article['titre'])) ?>"
                                data-article-id="<?= $article['id'] ?>">
                                <div class="article-top">
                                    <h3><?= htmlspecialchars($article['titre']) ?></h3>
                                    <div class="meta-sub">Par <?= htmlspecialchars($article['auteur']) ?> •
                                        <?= date('d/m/Y', strtotime($article['date_publication'])) ?></div>
                                </div>

                                <?php if ($mediaPath): ?>
                                    <div class="article-media">
                                        <div class="media-thumb"
                                            onclick="toggleZoom('<?= htmlspecialchars($mediaPath, ENT_QUOTES) ?>')">
                                            <?php if ($article['media_type'] === 'image'): ?>
                                                <img src="<?= htmlspecialchars($mediaPath) ?>" alt="media"
                                                    onerror="this.style.display='none'">
                                            <?php elseif ($article['media_type'] === 'video'): ?>
                                                <video src="<?= htmlspecialchars($mediaPath) ?>" muted loop playsinline></video>
                                            <?php elseif ($article['media_type'] === 'audio'): ?>
                                                <div class="audio-placeholder"><i class="fas fa-music"></i></div>
                                            <?php endif; ?>
                                            <div class="media-overlay"><i class="fas fa-search-plus"></i></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="article-actions">
                                    <button class="btn-warning" onclick="toggleEditForm(<?= $article['id'] ?>)"><i
                                            class="fas fa-edit"></i> Modifier</button>
                                    <form method="POST" onsubmit="return confirm('Supprimer cet article ?');"
                                        style="display:inline;">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                        <button class="btn-danger" type="submit"><i class="fas fa-trash"></i> Supprimer</button>
                                    </form>
                                    <button class="btn" type="button" onclick="showArticleModal(
                                <?= $article['id'] ?>,
                                <?= json_encode($article['titre']) ?>,
                                <?= json_encode($article['contenu']) ?>,
                                <?= json_encode($article['media_type']) ?>,
                                <?= json_encode($mediaPath) ?>
                            )">
                                        <i class="fas fa-eye"></i> Voir plus
                                    </button>
                                </div>

                                <!-- Formulaire de modification caché -->
                                <form class="edit-article-form" id="editForm-<?= $article['id'] ?>" action="" method="POST"
                                    enctype="multipart/form-data" style="display:none; margin-top:10px;">
                                    <input type="hidden" name="action" value="modifier">
                                    <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                    <div class="form-group">
                                        <label for="titre-<?= $article['id'] ?>">Titre</label>
                                        <input type="text" id="titre-<?= $article['id'] ?>" name="titre"
                                            value="<?= htmlspecialchars($article['titre']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contenu-<?= $article['id'] ?>">Contenu</label>
                                        <textarea id="contenu-<?= $article['id'] ?>" name="contenu" rows="4"
                                            required><?= htmlspecialchars($article['contenu']) ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="media-<?= $article['id'] ?>">Changer le média (optionnel)</label>
                                        <input type="file" id="media-<?= $article['id'] ?>" name="media"
                                            accept="image/*,video/*,audio/*">
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        <button type="button" class="btn btn-secondary"
                                            onclick="toggleEditForm(<?= $article['id'] ?>)">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Modale pour l'aperçu complet d'article -->
            <div id="articleModal" class="modal-overlay" style="display:none;">
                <div class="modal-content" id="articleModalContent">
                    <!-- Le contenu complet de l'article sera injecté ici -->
                    <button class="btn btn-secondary" onclick="closeArticleModal()" style="float:right;">Fermer</button>
                </div>
            </div>



            <footer class="admin-footer">
                <small>&copy; <?= date('Y') ?> Exauce aniti • Dashboard</small>
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