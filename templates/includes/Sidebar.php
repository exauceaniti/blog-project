<aside class="admin-sidebar">
    <!-- Header -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-feather-alt"></i>
            <span>Admin Panel</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Section Principale -->
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <ul>
                <li class="nav-item <?= ($_SESSION['current_page'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <a href="/admin/dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section Contenu -->
        <div class="nav-section">
            <div class="nav-section-title">Contenu</div>
            <ul>
                <li class="nav-item <?= ($_SESSION['current_page'] ?? '') === 'posts' ? 'active' : '' ?>">
                    <a href="/admin/posts" class="nav-link">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <span>Gestion des articles </span>
                        <!-- <span class="nav-badge">12</span> -->
                    </a>
                </li>
                <li class="nav-item <?= ($_SESSION['current_page'] ?? '') === 'categories' ? 'active' : '' ?>">
                    <a href="/admin/categories" class="nav-link">
                        <i class="fas fa-tags nav-icon"></i>
                        <span>Catégories</span>
                    </a>
                </li>
                <li class="nav-item <?= ($_SESSION['current_page'] ?? '') === 'comments' ? 'active' : '' ?>">
                    <a href="/admin/comments" class="nav-link">
                        <i class="fas fa-comments nav-icon"></i>
                        <span>Commentaires</span>
                        <span class="nav-badge">5</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section Utilisateurs -->
        <div class="nav-section">
            <div class="nav-section-title">Utilisateurs</div>
            <ul>
                <li class="nav-item <?= ($_SESSION['current_page'] ?? '') === 'users' ? 'active' : '' ?>">
                    <a href="/admin/users" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>
                <li class="nav-item <?= ($_SESSION['current_page'] ?? '') === 'roles' ? 'active' : '' ?>">
                    <a href="/admin/roles" class="nav-link">
                        <i class="fas fa-user-shield nav-icon"></i>
                        <span>Rôles</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section Système -->
        <div class="nav-section">
            <div class="nav-section-title">Système</div>
            <ul>
                <li class="nav-item <?= ($_SESSION['current_page'] ?? '') === 'settings' ? 'active' : '' ?>">
                    <a href="/admin/settings" class="nav-link">
                        <i class="fas fa-cog nav-icon"></i>
                        <span>Paramètres</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/backup" class="nav-link">
                        <i class="fas fa-database nav-icon"></i>
                        <span>Sauvegarde</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/logs" class="nav-link">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <span>Journaux</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <!-- Profil utilisateur -->
        <div class="user-profile">
            <div class="user-avatar">
                <?= strtoupper(substr($user_name ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= $user_name ?? 'Admin' ?></div>
                <div class="user-role">Administrateur</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="sidebar-actions">
            <a href="/" class="btn btn-outline btn-sm w-full">
                <i class="fas fa-arrow-left"></i>
                Retour au site
            </a>
            <a href="/logout" class="btn btn-logout btn-sm w-full">
                <i class="fas fa-sign-out-alt"></i>
                Déconnexion
            </a>
        </div>
    </div>
</aside>