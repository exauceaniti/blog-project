<?php

/**
 * templates/includes/header.php
 * * Variables attendues (définies par le LayoutController ou le BaseController) :
 * - $user_connected : bool (Authentification::isLoggedIn())
 * - $user_role : string (Authentification::getUserRole() ou $_SESSION['user']['role'])
 * - $user_name : string (Nom de l'utilisateur connecté)
 */
$user_connected = $user_connected ?? false;
$user_role = $user_role ?? null;
$user_name = $user_name ?? 'Utilisateur';
?>

<header class="site-header">
    <div class="header-container">
        <a href="/" class="logo">
            <i class="fas fa-feather-alt logo-icon"></i>
            <span class="logo-text">Exau-Blog</span>
        </a>

        <nav class="nav-main">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="/" class="nav-link <?= ($_SESSION['current_page'] ?? '') === 'home' ? 'active' : '' ?>">
                        <i class="fas fa-home nav-icon"></i>
                        <span class="nav-text">Accueil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/articles" class="nav-link <?= ($_SESSION['current_page'] ?? '') === 'articles' ? 'active' : '' ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <span class="nav-text">Articles</span>
                    </a>
                </li>

                <?php if ($user_connected): ?>
                    <li class="nav-item user-dropdown">
                        <a href="#" class="nav-link user-trigger">
                            <div class="user-avatar">
                                <?= strtoupper(substr($user_name ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="user-name"><?= htmlspecialchars($user_name) ?></span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>

                        <div class="dropdown-menu">
                            <?php if ($user_role === 'admin'): ?>
                                <a href="/admin/dashboard" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Dashboard-admin</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php else: ?>
                                <a href="/profile" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    <span>Mon Profil</span>
                                </a>
                            <?php endif; ?>

                            <a href="/settings" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Paramètres</span>
                            </a>
                            <a href="/logout" class="dropdown-item logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="/login" class="btn btn-outline btn-sm">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Connexion</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i>
                            <span>Inscription</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <button class="mobile-menu-btn" aria-label="Menu mobile">
            <span class="menu-line"></span>
            <span class="menu-line"></span>
            <span class="menu-line"></span>
        </button>
    </div>
</header>