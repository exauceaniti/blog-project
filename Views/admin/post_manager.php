<?php

/**
 * Views/admin/post_manager.php
 * Gestion des articles - Liste, édition, suppression
 * Reçoit $articles_list du PostController
 */
$articles_list = $articles_list ?? [];
?>

<!-- Header de la page -->
<div class="l-content-header">
    <div>
        <h1 class="l-header-title">📰 Gestion des Articles</h1>
        <p class="l-header-subtitle"><?= count($articles_list) ?> article(s) au total</p>
    </div>
    <a href="/admin/ajouter" class="c-btn c-btn--primary">
        <i class="fas fa-plus"></i> Créer un article
    </a>
</div>

<!-- Conteneur principal -->
<div class="l-posts-container">

    <!-- Filtres et recherche -->
    <div class="l-posts-toolbar">
        <div class="l-search-box">
            <input type="text" id="search-posts" class="l-search-input" placeholder="🔍 Chercher un article...">
        </div>
        <div class="l-filter-group">
            <select id="filter-status" class="l-filter-select">
                <option value="">Tous les statuts</option>
                <option value="published">Publiés</option>
                <option value="draft">Brouillons</option>
            </select>
            <select id="filter-author" class="l-filter-select">
                <option value="">Tous les auteurs</option>
            </select>
        </div>
    </div>

    <!-- Table des articles -->
    <?php if (!empty($articles_list)): ?>
        <div class="l-table-responsive">
            <table class="l-posts-table">
                <thead>
                    <tr>
                        <th class="l-table-col-id">#</th>
                        <th class="l-table-col-title">Titre</th>
                        <th class="l-table-col-author">Auteur</th>
                        <th class="l-table-col-date">Date</th>
                        <th class="l-table-col-media">Média</th>
                        <th class="l-table-col-comments">Commentaires</th>
                        <th class="l-table-col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles_list as $article): ?>
                        <tr class="l-post-row" data-post-id="<?= $article->id ?>">
                            <td class="l-table-col-id">
                                <span class="l-post-id">#<?= str_pad($article->id, 4, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            <td class="l-table-col-title">
                                <a href="/articles/<?= htmlspecialchars($article->id) ?>" target="_blank" class="l-post-title-link">
                                    <strong><?= htmlspecialchars(substr($article->titre, 0, 60)) ?></strong>
                                    <?php if (strlen($article->titre) > 60): ?>
                                        <span>...</span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td class="l-table-col-author">
                                <span class="l-author-badge">
                                    <?= htmlspecialchars($article->auteur_nom) ?>
                                </span>
                            </td>
                            <td class="l-table-col-date">
                                <span class="l-date-badge">
                                    <?= date('d/m/Y', strtotime($article->date_publication)) ?>
                                </span>
                            </td>
                            <td class="l-table-col-media">
                                <?php if (!empty($article->media_path)): ?>
                                    <span class="l-media-indicator l-media-indicator--present" title="Article avec média">
                                        <i class="fas fa-image"></i> Oui
                                    </span>
                                <?php else: ?>
                                    <span class="l-media-indicator l-media-indicator--absent" title="Pas de média">
                                        <i class="fas fa-times"></i> Non
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="l-table-col-comments">
                                <span class="l-comment-count">
                                    <i class="fas fa-comments"></i>
                                    <?= $article->comment_count ?? 0 ?>
                                </span>
                            </td>
                            <td class="l-table-col-actions">
                                <div class="l-action-group">
                                    <a href="/articles/<?= htmlspecialchars($article->id) ?>"
                                        class="c-btn c-btn--outline c-btn--sm"
                                        title="Voir l'article" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/modifier/ <?= $article->id ?>/edit"
                                        class="c-btn c-btn--primary c-btn--sm"
                                        title="Éditer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        class="c-btn c-btn--danger c-btn--sm"
                                        onclick="deletePost(<?= $article->id ?>)"
                                        title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination (si nécessaire) -->
        <div class="l-posts-pagination">
            <p class="l-pagination-info">
                Affichage de <strong>1</strong> à <strong><?= count($articles_list) ?></strong>
                sur <strong><?= count($articles_list) ?></strong> articles
            </p>
        </div>

    <?php else: ?>
        <!-- Empty state -->
        <div class="c-dashboard-card">
            <div class="l-empty-state">
                <div class="l-empty-state-icon">📝</div>
                <h3>Aucun article trouvé</h3>
                <p>Commencez par créer votre premier article!</p>
                <a href="/admin/posts/create" class="c-btn c-btn--primary" style="margin-top: var(--spacing-4);">
                    <i class="fas fa-plus"></i> Créer un article
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- Modal de confirmation suppression -->
<div id="delete-modal" class="l-modal" style="display: none;">
    <div class="l-modal-backdrop"></div>
    <div class="l-modal-content">
        <div class="l-modal-header">
            <h2>Confirmer la suppression</h2>
            <button type="button" class="l-modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="l-modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cet article?</p>
            <p class="l-modal-warning">⚠️ Cette action est irréversible</p>
        </div>
        <div class="l-modal-footer">
            <button type="button" class="c-btn c-btn--secondary" onclick="closeDeleteModal()">
                Annuler
            </button>
            <button type="button" class="c-btn c-btn--danger" onclick="confirmDelete()">
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>

<script>
    let postToDelete = null;

    function deletePost(postId) {
        postToDelete = postId;
        document.getElementById('delete-modal').style.display = 'flex';
    }

    function closeDeleteModal() {
        postToDelete = null;
        document.getElementById('delete-modal').style.display = 'none';
    }

    function confirmDelete() {
        if (!postToDelete) return;

        // TODO: Appel AJAX pour supprimer
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/posts/${postToDelete}/delete`;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }

    // Fermer le modal en cliquant sur le backdrop
    document.getElementById('delete-modal')?.addEventListener('click', (e) => {
        if (e.target.classList.contains('l-modal-backdrop')) {
            closeDeleteModal();
        }
    });

    // Recherche en temps réel (optionnel)
    document.getElementById('search-posts')?.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.l-post-row').forEach(row => {
            const title = row.querySelector('.l-post-title-link')?.textContent.toLowerCase();
            row.style.display = title?.includes(query) ? '' : 'none';
        });
    });
</script>