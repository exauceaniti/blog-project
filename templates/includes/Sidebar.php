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
<style>
    .admin-sidebar {
        display: flex;
        flex-direction: column;
        height: 100vh;
        background-color: #2c3e50;
        color: #ecf0f1;
    }

    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #34495e;
    }

    .sidebar-brand {
        font-size: 1.5em;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .sidebar-brand i {
        margin-right: 10px;
    }

    .sidebar-nav {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px 0;
    }

    .nav-section {
        margin-bottom: 20px;
    }

    .nav-section-title {
        padding: 10px 20px;
        font-size: 0.9em;
        text-transform: uppercase;
        color: #bdc3c7;
    }

    .nav-item {
        list-style: none;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        color: #ecf0f1;
        text-decoration: none;
    }

    .nav-link:hover,
    .nav-item.active .nav-link {
        background-color: #34495e;
    }

    .nav-icon {
        margin-right: 10px;
    }

    .nav-badge {
        background-color: #e74c3c;
        color: #fff;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 0.8em;
        margin-left: auto;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid #34495e;
    }

    .user-profile {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background-color: #2980b9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2em;
        margin-right: 10px;
    }

    .user-info .user-name {
        font-weight: bold;
    }

    .user-info .user-role {
        font-size: 0.85em;
        color: #bdc3c7;
    }

    .sidebar-actions .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        padding: 10px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9em;
        cursor: pointer;
    }

    .sidebar-actions .btn i {
        margin-right: 8px;
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid #ecf0f1;
        color: #ecf0f1;
    }

    .btn-outline:hover {
        background-color: #34495e;
    }

    .btn-logout {
        background-color: #e74c3c;
        color: #ecf0f1;
    }

    .btn-logout:hover {
        background-color: #c0392b;
    }
</style>