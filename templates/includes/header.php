<?php
$user_connected = $user_connected ?? false;
$user_role = $user_role ?? null;
?>

<header class="site-header">
    <div class="header-container">
        <!-- Logo -->
        <a href="/" class="logo">
            <i class="fas fa-feather-alt logo-icon"></i>
            <span class="logo-text">Exau-Blog</span>
        </a>

        <!-- Navigation principale -->
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
                    <!-- Menu utilisateur connecté -->
                    <li class="nav-item user-dropdown">
                        <a href="#" class="nav-link user-trigger">
                            <div class="user-avatar">
                                <?= strtoupper(substr($user_name ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="user-name"><?= htmlspecialchars($user_name ?? 'User') ?></span>
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
                    <!-- Menu visiteur -->
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

        <!-- Menu mobile -->
        <button class="mobile-menu-btn" aria-label="Menu mobile">
            <span class="menu-line"></span>
            <span class="menu-line"></span>
            <span class="menu-line"></span>
        </button>
    </div>
</header>

<style>
    /* ===== HEADER STYLES ===== */
    .site-header {
        background-color: var(--color-white);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 0;
        z-index: var(--z-40);
        border-bottom: var(--border-width-1) solid var(--color-secondary-200);
    }

    .header-container {
        max-width: var(--breakpoint-xl);
        margin: 0 auto;
        padding: 0 var(--spacing-6);
        height: var(--spacing-20);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Logo */
    .logo {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        text-decoration: none;
        font-weight: var(--font-weight-bold);
        font-size: var(--font-size-2xl);
        color: var(--color-primary-600);
        transition: color var(--transition-fast);
    }

    .logo:hover {
        color: var(--color-primary-700);
    }

    .logo-icon {
        font-size: var(--font-size-3xl);
    }

    .logo-text {
        font-family: var(--font-family-serif);
    }

    /* Navigation principale */
    .nav-main {
        display: flex;
    }

    .nav-list {
        display: flex;
        list-style: none;
        gap: var(--spacing-6);
        align-items: center;
        margin: 0;
        padding: 0;
    }

    /* Items de navigation */
    .nav-item {
        position: relative;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);
        padding: var(--spacing-2) var(--spacing-3);
        color: var(--color-secondary-700);
        text-decoration: none;
        font-weight: var(--font-weight-medium);
        font-size: var(--font-size-base);
        border-radius: var(--radius-md);
        transition: all var(--transition-fast);
        position: relative;
    }

    .nav-link:hover {
        color: var(--color-primary-600);
        background-color: var(--color-secondary-50);
    }

    .nav-link.active {
        color: var(--color-primary-600);
        background-color: var(--color-primary-50);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: calc(-1 * var(--spacing-1));
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: var(--border-width-2);
        background-color: var(--color-primary-600);
        border-radius: var(--radius-full);
    }

    .nav-icon {
        font-size: var(--font-size-lg);
        width: var(--spacing-6);
        text-align: center;
    }

    /* Menu utilisateur dropdown */
    .user-dropdown {
        position: relative;
    }

    .user-trigger {
        padding: var(--spacing-2) var(--spacing-3);
    }

    .user-avatar {
        width: var(--spacing-8);
        height: var(--spacing-8);
        border-radius: var(--radius-full);
        background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-info-500) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-white);
        font-weight: var(--font-weight-semibold);
        font-size: var(--font-size-base);
    }

    .user-name {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dropdown-arrow {
        font-size: var(--font-size-sm);
        transition: transform var(--transition-fast);
    }

    .user-dropdown:hover .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown menu */
    .dropdown-menu {
        position: absolute;
        top: calc(100% + var(--spacing-2));
        right: 0;
        width: 220px;
        background-color: var(--color-white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border: var(--border-width-1) solid var(--color-secondary-200);
        padding: var(--spacing-2) 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all var(--transition-fast);
        z-index: var(--z-50);
    }

    .user-dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        padding: var(--spacing-3) var(--spacing-4);
        color: var(--color-secondary-700);
        text-decoration: none;
        font-size: var(--font-size-sm);
        transition: all var(--transition-fast);
    }

    .dropdown-item:hover {
        background-color: var(--color-secondary-50);
        color: var(--color-primary-600);
        padding-left: var(--spacing-5);
    }

    .dropdown-item i {
        width: var(--spacing-5);
        text-align: center;
        font-size: var(--font-size-base);
    }

    .dropdown-item.logout {
        color: var(--color-danger-600);
    }

    .dropdown-item.logout:hover {
        background-color: var(--color-danger-50);
        color: var(--color-danger-700);
    }

    .dropdown-divider {
        height: var(--border-width-1);
        background-color: var(--color-secondary-200);
        margin: var(--spacing-2) 0;
    }

    /* Boutons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-2);
        padding: var(--spacing-2) var(--spacing-4);
        border-radius: var(--radius-md);
        font-weight: var(--font-weight-medium);
        font-size: var(--font-size-sm);
        text-decoration: none;
        transition: all var(--transition-fast);
        border: var(--border-width-1) solid transparent;
        cursor: pointer;
    }

    .btn-sm {
        padding: var(--spacing-1-5) var(--spacing-3);
        font-size: var(--font-size-xs);
    }

    .btn-primary {
        background-color: var(--color-primary-600);
        color: var(--color-white);
        border-color: var(--color-primary-600);
    }

    .btn-primary:hover {
        background-color: var(--color-primary-700);
        border-color: var(--color-primary-700);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--color-primary-600);
        border-color: var(--color-primary-600);
    }

    .btn-outline:hover {
        background-color: var(--color-primary-50);
        transform: translateY(-1px);
    }

    .btn-ghost {
        background-color: transparent;
        color: var(--color-secondary-700);
        border-color: transparent;
    }

    .btn-ghost:hover {
        background-color: var(--color-secondary-100);
        color: var(--color-secondary-900);
    }

    /* Menu mobile */
    .mobile-menu-btn {
        display: none;
        flex-direction: column;
        justify-content: space-between;
        width: var(--spacing-8);
        height: var(--spacing-6);
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .menu-line {
        width: 100%;
        height: var(--border-width-2);
        background-color: var(--color-secondary-700);
        border-radius: var(--radius-full);
        transition: all var(--transition-fast);
    }

    .mobile-menu-btn:hover .menu-line {
        background-color: var(--color-primary-600);
    }

    /* ===== RESPONSIVE DESIGN ===== */

    /* Tablette */
    @media (max-width: 1024px) {
        .header-container {
            padding: 0 var(--spacing-4);
        }

        .nav-list {
            gap: var(--spacing-4);
        }

        .logo-text {
            font-size: var(--font-size-xl);
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .mobile-menu-btn {
            display: flex;
        }

        .nav-main {
            position: fixed;
            top: var(--spacing-20);
            left: 0;
            right: 0;
            background-color: var(--color-white);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-4);
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-normal);
            z-index: var(--z-30);
        }

        .nav-main.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .nav-list {
            flex-direction: column;
            align-items: stretch;
            gap: var(--spacing-2);
        }

        .nav-link {
            padding: var(--spacing-3);
            justify-content: flex-start;
        }

        .nav-link.active::after {
            left: 0;
            transform: none;
            width: var(--border-width-2);
            height: 100%;
            bottom: 0;
        }

        .user-dropdown .dropdown-menu {
            position: static;
            width: 100%;
            box-shadow: none;
            border: none;
            background-color: var(--color-secondary-50);
            opacity: 1;
            visibility: visible;
            transform: none;
            margin-top: var(--spacing-2);
            padding: var(--spacing-2);
        }

        .user-trigger .user-name,
        .user-trigger .dropdown-arrow {
            display: none;
        }

        .mobile-menu-btn.active .menu-line:nth-child(1) {
            transform: rotate(45deg) translate(var(--spacing-1), var(--spacing-1));
        }

        .mobile-menu-btn.active .menu-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active .menu-line:nth-child(3) {
            transform: rotate(-45deg) translate(var(--spacing-1), calc(-1 * var(--spacing-1)));
        }
    }

    /* Petits mobiles */
    @media (max-width: 480px) {
        .header-container {
            padding: 0 var(--spacing-3);
        }

        .logo span:not(.logo-icon) {
            font-size: var(--font-size-lg);
        }

        .logo-icon {
            font-size: var(--font-size-2xl);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navMain = document.querySelector('.nav-main');

        if (mobileMenuBtn && navMain) {
            mobileMenuBtn.addEventListener('click', function() {
                navMain.classList.toggle('active');
                this.classList.toggle('active');
            });

            // Fermer le menu en cliquant sur un lien
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        navMain.classList.remove('active');
                        mobileMenuBtn.classList.remove('active');
                    }
                });
            });

            // Fermer en cliquant à l'extérieur
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 &&
                    !navMain.contains(e.target) &&
                    !mobileMenuBtn.contains(e.target)) {
                    navMain.classList.remove('active');
                    mobileMenuBtn.classList.remove('active');
                }
            });
        }

        // Gestion des dropdowns sur mobile
        const userTriggers = document.querySelectorAll('.user-trigger');
        userTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth > 768) {
                    e.preventDefault();
                    return;
                }

                e.preventDefault();
                const dropdown = this.nextElementSibling;
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
        });
    });
</script>