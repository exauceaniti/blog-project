<?php
$user_connected = $user_connected ?? false;
$user_role = $user_role ?? null;
?>

<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <a href="/" class="logo">
            <i class="fas fa-feather-alt logo-icon"></i>
            <span>MonBlog</span>
        </a>

        <!-- Navigation principale -->
        <nav class="nav-main">
            <ul>
                <li>
                    <a href="/" class="nav-link <?= ($_SESSION['current_page'] ?? '') === 'home' ? 'active' : '' ?>">
                        <i class="fas fa-home nav-icon"></i>
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="/articles" class="nav-link <?= ($_SESSION['current_page'] ?? '') === 'articles' ? 'active' : '' ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        Articles
                    </a>
                </li>

                <?php if ($user_connected): ?>
                    <!-- Menu utilisateur connecté -->
                    <li class="user-dropdown">
                        <a href="#" class="nav-link user-trigger">
                            <div class="user-avatar">
                                <?= strtoupper(substr($user_name ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="user-name"><?= $user_name ?? 'User' ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </a>

                        <div class="dropdown-menu">
                            <?php if ($user_role === 'admin'): ?>
                                <a href="/admin/dashboard" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard-admin
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php else: ?>
                                <a href="/profile" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    Mon Profil
                                </a>
                            <?php endif; ?>

                            <a href="/settings" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                Paramètres
                            </a>
                            <a href="/logout" class="dropdown-item logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Déconnexion
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- Menu visiteur -->
                    <li>
                        <a href="/login" class="btn btn-ghost btn-sm">
                            <i class="fas fa-sign-in-alt"></i>
                            Connexion
                        </a>
                    </li>
                    <li>
                        <a href="/register" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i>
                            Inscription
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Menu mobile a ajouter avec le temps-->
        <button class="mobile-menu-btn" aria-label="Menu mobile">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>