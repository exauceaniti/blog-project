<aside class="l-admin-sidebar">
    <!-- Header -->
    <div class="sidebar-header">
        <div class="l-sidebar-brand">
            <a href="/admin/dashboard" class="l-brand-logo">
                <i class="fas fa-feather-alt"></i>
                <span class="l-brand-text">Admin Panel</span>
            </a>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="l-sidebar-nav">
        <!-- Section Principale -->
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <ul>
                <li class="l-nav-item <?= ($_SESSION['current_page'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <a href="/admin/dashboard" class="l-nav-link <?= ($_SESSION['current_page'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="l-nav-text">Tableau de bord</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section Contenu -->
        <div class="nav-section">
            <div class="nav-section-title">Contenu</div>
            <ul>
                <li class="l-nav-item <?= ($_SESSION['current_page'] ?? '') === 'posts' ? 'active' : '' ?>">
                    <a href="/admin/posts" class="l-nav-link <?= ($_SESSION['current_page'] ?? '') === 'posts' ? 'active' : '' ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <span class="l-nav-text">Gestion des articles </span>
                    </a>
                </li>
                <li class="l-nav-item <?= ($_SESSION['current_page'] ?? '') === 'categories' ? 'active' : '' ?>">
                    <a href="/admin/categories" class="l-nav-link <?= ($_SESSION['current_page'] ?? '') === 'categories' ? 'active' : '' ?>">
                        <i class="fas fa-tags nav-icon"></i>
                        <span class="l-nav-text">Catégories</span>
                    </a>
                </li>
                <li class="l-nav-item <?= ($_SESSION['current_page'] ?? '') === 'comments' ? 'active' : '' ?>">
                    <a href="/admin/comments" class="l-nav-link <?= ($_SESSION['current_page'] ?? '') === 'comments' ? 'active' : '' ?>">
                        <i class="fas fa-comments nav-icon"></i>
                        <span class="l-nav-text">Commentaires</span>
                        <span class="l-badge">5</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section Utilisateurs -->
        <div class="nav-section">
            <div class="nav-section-title">Utilisateurs</div>
            <ul>
                <li class="l-nav-item <?= ($_SESSION['current_page'] ?? '') === 'users' ? 'active' : '' ?>">
                    <a href="/admin/users" class="l-nav-link <?= ($_SESSION['current_page'] ?? '') === 'users' ? 'active' : '' ?>">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="l-nav-text">Utilisateurs</span>
                    </a>
                </li>
                <li class="l-nav-item <?= ($_SESSION['current_page'] ?? '') === 'roles' ? 'active' : '' ?>">
                    <a href="/admin/roles" class="l-nav-link <?= ($_SESSION['current_page'] ?? '') === 'roles' ? 'active' : '' ?>">
                        <i class="fas fa-user-shield nav-icon"></i>
                        <span class="l-nav-text">Rôles</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section Système -->
        <div class="nav-section">
            <div class="nav-section-title">Système</div>
            <ul>
                <li class="l-nav-item <?= ($_SESSION['current_page'] ?? '') === 'settings' ? 'active' : '' ?>">
                    <a href="/admin/settings" class="l-nav-link <?= ($_SESSION['current_page'] ?? '') === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog nav-icon"></i>
                        <span class="l-nav-text">Paramètres</span>
                    </a>
                </li>
                <li class="l-nav-item">
                    <a href="/admin/backup" class="l-nav-link">
                        <i class="fas fa-database nav-icon"></i>
                        <span class="l-nav-text">Sauvegarde</span>
                    </a>
                </li>
                <li class="l-nav-item">
                    <a href="/admin/logs" class="l-nav-link">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <span class="l-nav-text">Journaux</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Footer -->
    <div class="l-sidebar-footer">
        <!-- Profil utilisateur -->
        <div class="l-user-profile">
            <div class="l-user-avatar">
                <?= strtoupper(substr($user_name ?? 'A', 0, 1)) ?>
            </div>
            <div class="l-user-info">
                <p class="l-user-name"><?= $user_name ?? 'Admin' ?></p>
                <p class="l-user-role">Administrateur</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="sidebar-actions">
            <a href="/" class="c-btn c-btn--secondary c-btn--sm" style="width: 100%; margin-bottom: var(--spacing-2);">
                <i class="fas fa-arrow-left"></i>
                Retour au site
            </a>
            <a href="/logout" class="c-btn c-btn--danger c-btn--sm" style="width: 100%;">
                <i class="fas fa-sign-out-alt"></i>
                Déconnexion
            </a>
        </div>
    </div>
</aside>
</style>