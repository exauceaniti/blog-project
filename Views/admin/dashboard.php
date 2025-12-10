<?php

/**
 * Views/admin/dashboard.php
 * Dashboard administrateur - Vue d'ensemble et statistiques
 */
?>

<!-- Header de la page -->
<div class="l-content-header">
    <h1 class="l-header-title">📊 Tableau de bord</h1>
    <p class="l-header-subtitle">Vue d'ensemble de votre blog</p>
</div>

<!-- Conteneur principal -->
<div class="l-dashboard-container">

    <!-- Row 1: KPIs Statistiques -->
    <div class="l-dashboard-row">
        <!-- Card: Total Articles -->
        <div class="c-dashboard-card">
            <div class="c-dashboard-card__header">
                <h3 class="c-dashboard-card__title">Articles publiés</h3>
                <span class="c-dashboard-card__icon">📰</span>
            </div>
            <div class="c-dashboard-card__value">
                <?= $stats['total_posts'] ?? 0 ?>
            </div>
            <div class="c-dashboard-card__change c-dashboard-card__change--up">
                <span>↑</span>
                <span><?= $stats['posts_this_month'] ?? 0 ?> ce mois</span>
            </div>
        </div>

        <!-- Card: Total Utilisateurs -->
        <div class="c-dashboard-card">
            <div class="c-dashboard-card__header">
                <h3 class="c-dashboard-card__title">Utilisateurs actifs</h3>
                <span class="c-dashboard-card__icon">👥</span>
            </div>
            <div class="c-dashboard-card__value">
                <?= $stats['total_users'] ?? 0 ?>
            </div>
            <div class="c-dashboard-card__change c-dashboard-card__change--up">
                <span>↑</span>
                <span><?= $stats['users_this_month'] ?? 0 ?> nouveaux</span>
            </div>
        </div>

        <!-- Card: Total Commentaires -->
        <div class="c-dashboard-card">
            <div class="c-dashboard-card__header">
                <h3 class="c-dashboard-card__title">Commentaires</h3>
                <span class="c-dashboard-card__icon">💬</span>
            </div>
            <div class="c-dashboard-card__value">
                <?= $stats['total_comments'] ?? 0 ?>
            </div>
            <div class="c-dashboard-card__change c-dashboard-card__change--up">
                <span>↑</span>
                <span><?= $stats['pending_comments'] ?? 0 ?> à modérer</span>
            </div>
        </div>

        <!-- Card: Vues total -->
        <div class="c-dashboard-card">
            <div class="c-dashboard-card__header">
                <h3 class="c-dashboard-card__title">Visiteurs</h3>
                <span class="c-dashboard-card__icon">👁️</span>
            </div>
            <div class="c-dashboard-card__value">
                <?= number_format($stats['total_views'] ?? 0, 0, ',', ' ') ?>
            </div>
            <div class="c-dashboard-card__change c-dashboard-card__change--up">
                <span>↑</span>
                <span><?= $stats['views_today'] ?? 0 ?> aujourd'hui</span>
            </div>
        </div>
    </div>

    <!-- Row 2: Articles Récents & Commentaires -->
    <div class="l-dashboard-row">
        <!-- Colonne Gauche: Articles Récents -->
        <div class="l-dashboard-col">
            <div class="c-dashboard-card c-dashboard-card--full">
                <div class="c-dashboard-card__header">
                    <h3 class="c-dashboard-card__title">Articles récents</h3>
                    <a href="/admin/posts" class="c-btn c-btn--primary c-btn--sm">Voir tous</a>
                </div>

                <?php if (!empty($recent_posts)): ?>
                    <div class="l-table-responsive">
                        <table class="l-table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Auteur</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_posts as $post): ?>
                                    <tr>
                                        <td class="l-table__cell-title">
                                            <strong><?= htmlspecialchars(substr($post->titre, 0, 40)) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($post->auteur_nom) ?></td>
                                        <td><?= date('d/m/Y', strtotime($post->date_publication)) ?></td>
                                        <td>
                                            <span class="l-badge l-badge--success">Publié</span>
                                        </td>
                                        <td>
                                            <a href="/admin/posts/<?= $post->id ?>/edit" class="c-btn c-btn--outline c-btn--sm">Éditer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="l-empty-state">
                        <p>📝 Aucun article récemment publié.</p>
                        <a href="/admin/ajouter" class="c-btn c-btn--primary">Créer un article</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colonne Droite: Commentaires en attente -->
        <div class="l-dashboard-col">
            <div class="c-dashboard-card c-dashboard-card--full">
                <div class="c-dashboard-card__header">
                    <h3 class="c-dashboard-card__title">Commentaires à modérer</h3>
                    <a href="/admin/comments" class="c-btn c-btn--primary c-btn--sm">Tous</a>
                </div>

                <?php if (!empty($pending_comments)): ?>
                    <div class="l-comments-list">
                        <?php foreach ($pending_comments as $comment): ?>
                            <div class="l-comment-item">
                                <div class="l-comment-header">
                                    <strong><?= htmlspecialchars($comment->user_name) ?></strong>
                                    <span class="l-comment-date"><?= date('d/m/Y H:i', strtotime($comment->date_creation)) ?></span>
                                </div>
                                <p class="l-comment-text">
                                    <?= htmlspecialchars(substr($comment->contenu, 0, 100)) ?>...
                                </p>
                                <div class="l-comment-actions">
                                    <button class="c-btn c-btn--success c-btn--sm" onclick="approveComment(<?= $comment->id ?>)">
                                        ✓ Approuver
                                    </button>
                                    <button class="c-btn c-btn--danger c-btn--sm" onclick="rejectComment(<?= $comment->id ?>)">
                                        ✗ Rejeter
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="l-empty-state">
                        <p>✅ Tous les commentaires ont été modérés!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Row 3: Activité -->
    <div class="l-dashboard-row">
        <div class="c-dashboard-card c-dashboard-card--full">
            <div class="c-dashboard-card__header">
                <h3 class="c-dashboard-card__title">Activité récente</h3>
            </div>

            <div class="l-activity-timeline">
                <div class="l-activity-item">
                    <div class="l-activity-icon">📝</div>
                    <div class="l-activity-content">
                        <p class="l-activity-title"><strong>5 articles</strong> publiés ce mois</p>
                        <p class="l-activity-time">Mis à jour il y a 2 heures</p>
                    </div>
                </div>

                <div class="l-activity-item">
                    <div class="l-activity-icon">👥</div>
                    <div class="l-activity-content">
                        <p class="l-activity-title"><strong>12 utilisateurs</strong> actifs aujourd'hui</p>
                        <p class="l-activity-time">Mis à jour il y a 30 minutes</p>
                    </div>
                </div>

                <div class="l-activity-item">
                    <div class="l-activity-icon">💬</div>
                    <div class="l-activity-content">
                        <p class="l-activity-title"><strong>3 nouveaux commentaires</strong> en attente de modération</p>
                        <p class="l-activity-time">Mis à jour il y a 15 minutes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function approveComment(commentId) {
        // TODO: Appel AJAX pour approuver le commentaire
        console.log('Approuver commentaire:', commentId);
    }

    function rejectComment(commentId) {
        // TODO: Appel AJAX pour rejeter le commentaire
        console.log('Rejeter commentaire:', commentId);
    }
</script>