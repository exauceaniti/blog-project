<?php
// admin/pages/dashboard.php
?>

<div class="stats-grid">
    <div class="stat-card">
        <i class="fas fa-newspaper" style="color: var(--accent-1);"></i>
        <h3><?= $nbArticles ?></h3>
        <p>Articles Publiés</p>
    </div>
    <div class="stat-card">
        <i class="fas fa-users" style="color: var(--accent-2);"></i>
        <h3><?= $nbUsers ?></h3>
        <p>Utilisateurs Inscrits</p>
    </div>
    <div class="stat-card">
        <i class="fas fa-comments" style="color: var(--accent-3);"></i>
        <h3><?= $nbCommentaires ?></h3>
        <p>Commentaires</p>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 1rem;">Actions Rapides</h2>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="?page=manage_posts" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvel Article
        </a>
        <a href="?page=manage_users" class="btn btn-secondary">
            <i class="fas fa-user-cog"></i> Gérer Utilisateurs
        </a>
        <a href="?page=manage_comments" class="btn btn-secondary">
            <i class="fas fa-comment-dots"></i> Modérer Commentaires
        </a>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 1rem;">Activité Récente</h2>
    <p style="color: var(--text-secondary); text-align: center; padding: 2rem;">
        <i class="fas fa-chart-line fa-2x" style="margin-bottom: 1rem;"></i><br>
        Graphiques et statistiques détaillées à venir...
    </p>
</div>