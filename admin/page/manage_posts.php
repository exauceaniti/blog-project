<?php
// admin/pages/manage_posts.php
session_start();

require_once "../config/connexion.php";
require_once "../models/Post.php";

$connexion = new Connexion();
$postManager = new Post($connexion);

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'ajouter':
            $titre = trim($_POST['titre']);
            $contenu = trim($_POST['contenu']);
            $auteurId = $_SESSION['user_id'];

            if ($titre && $contenu) {
                $fichier = $_FILES['media'] ?? null;
                $postManager->ajouterArticle($titre, $contenu, $auteurId, $fichier);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article ajouté avec succès!'];
                header("Location: ?page=manage_posts");
                exit;
            }
            break;

        case 'modifier':
            $id = (int)$_POST['id'];
            $titre = trim($_POST['titre']);
            $contenu = trim($_POST['contenu']);

            if ($id && $titre && $contenu) {
                $fichier = $_FILES['media'] ?? null;
                $postManager->modifierArticle($id, $titre, $contenu, $fichier);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article modifié avec succès!'];
                header("Location: ?page=manage_posts");
                exit;
            }
            break;

        case 'supprimer':
            $id = (int)$_POST['id'];
            if ($id) {
                $postManager->supprimerArticle($id);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Article supprimé avec succès!'];
                header("Location: ?page=manage_posts");
                exit;
            }
            break;
    }
}

$articles = $postManager->voirArticles();
?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-plus-circle"></i> Ajouter un Nouvel Article
    </h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter">

        <div class="form-group">
            <label for="titre">Titre de l'article</label>
            <input type="text" id="titre" name="titre" placeholder="Entrez un titre accrocheur..." required>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu de l'article</label>
            <textarea id="contenu" name="contenu" placeholder="Rédigez votre contenu ici..." rows="6" required></textarea>
        </div>

        <div class="form-group">
            <label for="media">Média (optionnel)</label>
            <input type="file" name="media" id="media" accept="image/*,video/*,audio/*">
            <small style="color: var(--text-secondary);">Formats supportés: JPG, PNG, MP4, MP3</small>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Publier l'Article
        </button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-edit"></i> Modifier un Article
    </h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="modifier">

        <div class="form-group">
            <label for="modifier_id">ID de l'article à modifier</label>
            <input type="number" id="modifier_id" name="id" placeholder="ID de l'article" required>
        </div>

        <div class="form-group">
            <label for="modifier_titre">Nouveau titre</label>
            <input type="text" id="modifier_titre" name="titre" placeholder="Nouveau titre" required>
        </div>

        <div class="form-group">
            <label for="modifier_contenu">Nouveau contenu</label>
            <textarea id="modifier_contenu" name="contenu" placeholder="Nouveau contenu" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="modifier_media">Nouveau média (optionnel)</label>
            <input type="file" name="media" id="modifier_media" accept="image/*,video/*,audio/*">
            <small style="color: var(--text-secondary);">Laisser vide pour conserver le média actuel</small>
        </div>

        <button type="submit" class="btn btn-warning">
            <i class="fas fa-save"></i> Modifier l'Article
        </button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-trash"></i> Supprimer un Article
    </h2>

    <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
        <input type="hidden" name="action" value="supprimer">

        <div class="form-group">
            <label for="supprimer_id">ID de l'article à supprimer</label>
            <input type="number" id="supprimer_id" name="id" placeholder="ID de l'article" required>
        </div>

        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash"></i> Supprimer l'Article
        </button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-list-alt"></i> Liste des Articles (<?= count($articles) ?>)
    </h2>

    <?php if (empty($articles)): ?>
        <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
            <i class="fas fa-newspaper fa-3x" style="margin-bottom: 1rem;"></i>
            <p>Aucun article publié pour le moment.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 1.5rem;">
            <?php foreach ($articles as $article): ?>
                <div style="padding: 1.5rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-tertiary);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div style="flex: 1;">
                            <h3 style="margin-bottom: 0.5rem; color: var(--primary-color);">
                                <?= htmlspecialchars($article['titre']) ?>
                            </h3>
                            <div style="color: var(--text-secondary); font-size: 0.9rem;">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($article['auteur']) ?> •
                                <i class="fas fa-calendar"></i> <?= date('d/m/Y à H:i', strtotime($article['date_publication'])) ?> •
                                <i class="fas fa-id-card"></i> ID: <?= $article['id'] ?>
                            </div>
                        </div>
                    </div>

                    <div style="color: var(--text-primary); margin-bottom: 1rem; line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($article['contenu'])) ?>
                    </div>

                    <?php if (!empty($article['media_path'])): ?>
                        <div style="margin-top: 1rem;">
                            <strong style="color: var(--text-secondary);">Média associé :</strong>
                            <?php
                            $mediaPath = strpos($article['media_path'], 'assets/uploads/') === 0
                                ? "../" . $article['media_path']
                                : "../assets/uploads/" . $article['media_path'];
                            ?>

                            <?php if ($article['media_type'] === 'image'): ?>
                                <div style="margin-top: 0.5rem;">
                                    <img src="<?= htmlspecialchars($mediaPath) ?>"
                                        alt="Image de l'article"
                                        style="max-width: 300px; border-radius: 8px; border: 1px solid var(--border-color);"
                                        onerror="this.style.display='none'">
                                </div>
                            <?php elseif ($article['media_type'] === 'video'): ?>
                                <div style="margin-top: 0.5rem;">
                                    <video controls style="max-width: 300px; border-radius: 8px;">
                                        <source src="<?= htmlspecialchars($mediaPath) ?>" type="video/mp4">
                                        Votre navigateur ne supporte pas la vidéo.
                                    </video>
                                </div>
                            <?php elseif ($article['media_type'] === 'audio'): ?>
                                <div style="margin-top: 0.5rem;">
                                    <audio controls style="width: 100%">
                                        <source src="<?= htmlspecialchars($mediaPath) ?>" type="audio/mpeg">
                                        Votre navigateur ne supporte pas l'audio.
                                    </audio>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Remplir automatiquement les formulaires de modification/suppression quand on clique sur un article
    document.addEventListener('DOMContentLoaded', function() {
        const articles = document.querySelectorAll('.card > div > div'); // Sélectionne chaque carte d'article

        articles.forEach(article => {
            article.addEventListener('click', function() {
                const id = this.querySelector('i.fa-id-card')?.parentNode?.textContent?.match(/ID: (\d+)/)?.[1];
                const titre = this.querySelector('h3')?.textContent;
                const contenu = this.querySelector('div[style*="line-height: 1.6"]')?.textContent;

                if (id) {
                    // Remplir le formulaire de modification
                    document.getElementById('modifier_id').value = id;
                    document.getElementById('modifier_titre').value = titre || '';
                    document.getElementById('modifier_contenu').value = contenu || '';

                    // Remplir le formulaire de suppression
                    document.getElementById('supprimer_id').value = id;
                }
            });
        });
    });
</script>