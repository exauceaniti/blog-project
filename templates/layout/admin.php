<!DOCTYPE html>
<html lang="fr">

<head>
    <?php \Src\Core\Render\Fragment::meta($page_title ?? 'Admin - Dashboard'); ?>
</head>

<body class="admin-layout">
    <aside class="admin-sidebar">
        <?php \Src\Core\Render\Fragment::sidebar($sidebar_params ?? []); ?>
    </aside>

    <main class="admin-main-content">
        <div class="content-wrapper">
            <?= $page_view ?>
        </div>
    </main>
</body>

</html>

<style>
    /* ===== LAYOUT PRINCIPAL UTILITAIRE ===== */
    .admin-layout {
        display: flex;
        min-height: 100vh;
        background-color: var(--color-secondary-50);
        overflow-x: hidden;
    }

    /* ===== SIDEBAR ===== */
    .admin-sidebar {
        width: 280px;
        background: linear-gradient(180deg, var(--color-secondary-900) 0%, #0f172a 100%);
        color: var(--color-secondary-100);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: var(--z-50);
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-xl);
        border-right: var(--border-width-1) solid rgba(255, 255, 255, 0.1);
    }

    .admin-sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--color-primary-400), transparent);
    }

    /* Logo/Brand area */
    .admin-sidebar .sidebar-brand {
        padding: var(--spacing-6) var(--spacing-5);
        border-bottom: var(--border-width-1) solid rgba(255, 255, 255, 0.1);
        margin-bottom: var(--spacing-2);
    }

    .admin-sidebar .brand-logo {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        color: var(--color-white);
        text-decoration: none;
        font-weight: var(--font-weight-bold);
        font-size: var(--font-size-xl);
    }

    .admin-sidebar .brand-logo i {
        font-size: var(--font-size-2xl);
        color: var(--color-primary-500);
    }

    /* Navigation */
    .admin-sidebar .sidebar-nav {
        flex: 1;
        padding: var(--spacing-4) 0;
        overflow-y: auto;
    }

    /* Menu items */
    .admin-sidebar .nav-item {
        margin: var(--spacing-1) var(--spacing-3);
    }

    .admin-sidebar .nav-link {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        padding: var(--spacing-3) var(--spacing-4);
        color: var(--color-secondary-100);
        text-decoration: none;
        border-radius: var(--radius-lg);
        transition: all var(--transition-fast);
        font-weight: var(--font-weight-medium);
        position: relative;
        overflow: hidden;
    }

    .admin-sidebar .nav-link:hover {
        background-color: var(--color-secondary-700);
        transform: translateX(var(--spacing-1));
    }

    .admin-sidebar .nav-link.active {
        background-color: var(--color-primary-600);
        color: var(--color-white);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .admin-sidebar .nav-link i {
        font-size: var(--font-size-lg);
        min-width: var(--spacing-6);
        text-align: center;
    }

    .admin-sidebar .nav-link .badge {
        margin-left: auto;
        background-color: var(--color-danger-500);
        color: var(--color-white);
        font-size: var(--font-size-xs);
        padding: var(--spacing-1) var(--spacing-2);
        border-radius: var(--radius-full);
        font-weight: var(--font-weight-semibold);
    }

    /* Sidebar footer */
    .admin-sidebar .sidebar-footer {
        padding: var(--spacing-5);
        border-top: var(--border-width-1) solid rgba(255, 255, 255, 0.1);
        margin-top: auto;
    }

    .admin-sidebar .user-profile {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        padding: var(--spacing-3);
        border-radius: var(--radius-lg);
        transition: background-color var(--transition-fast);
    }

    .admin-sidebar .user-profile:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .admin-sidebar .user-avatar {
        width: var(--spacing-10);
        height: var(--spacing-10);
        border-radius: var(--radius-full);
        background: linear-gradient(135deg, var(--color-primary-400) 0%, var(--color-info-500) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: var(--font-weight-semibold);
        color: var(--color-white);
    }

    .admin-sidebar .user-info {
        flex: 1;
    }

    .admin-sidebar .user-name {
        font-weight: var(--font-weight-semibold);
        font-size: var(--font-size-sm);
        color: var(--color-white);
    }

    .admin-sidebar .user-role {
        font-size: var(--font-size-xs);
        color: rgba(255, 255, 255, 0.7);
    }

    /* ===== CONTENU PRINCIPAL ===== */
    .admin-main-content {
        flex: 1;
        margin-left: 280px;
        min-height: 100vh;
        transition: margin-left var(--transition-normal);
    }

    .content-wrapper {
        padding: var(--spacing-8);
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== HEADER FIXE ===== */
    .content-header {
        background-color: var(--color-white);
        padding: var(--spacing-6) var(--spacing-8);
        margin: calc(-1 * var(--spacing-8)) calc(-1 * var(--spacing-8)) var(--spacing-8) calc(-1 * var(--spacing-8));
        border-bottom: var(--border-width-1) solid var(--color-secondary-200);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 0;
        z-index: var(--z-40);
    }

    .content-header .header-title {
        font-size: var(--font-size-2xl);
        font-weight: var(--font-weight-bold);
        color: var(--color-secondary-800);
        margin: 0;
    }

    .content-header .header-subtitle {
        font-size: var(--font-size-sm);
        color: var(--color-secondary-500);
        margin-top: var(--spacing-1);
    }

    /* ===== COMPOSANTS ADMIN ===== */
    .dashboard-card {
        background-color: var(--color-white);
        border-radius: var(--radius-lg);
        padding: var(--spacing-6);
        box-shadow: var(--shadow-md);
        border: var(--border-width-1) solid var(--color-secondary-200);
        transition: transform var(--transition-normal), box-shadow var(--transition-normal);
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .dashboard-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--spacing-5);
    }

    .dashboard-card .card-title {
        font-size: var(--font-size-base);
        font-weight: var(--font-weight-semibold);
        color: var(--color-secondary-600);
        margin: 0;
    }

    .dashboard-card .card-value {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-bold);
        color: var(--color-secondary-900);
        margin: var(--spacing-2) 0;
    }

    .dashboard-card .card-change {
        display: flex;
        align-items: center;
        gap: var(--spacing-1);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
    }

    .dashboard-card .change-up {
        color: var(--color-success-500);
    }

    .dashboard-card .change-down {
        color: var(--color-danger-500);
    }

    /* ===== RESPONSIVE DESIGN ===== */

    /* Tablette */
    @media (max-width: 1024px) {
        .admin-sidebar {
            width: 70px;
        }

        .admin-sidebar .brand-text,
        .admin-sidebar .nav-text,
        .admin-sidebar .user-info {
            display: none;
        }

        .admin-sidebar .brand-logo {
            justify-content: center;
        }

        .admin-sidebar .nav-link {
            justify-content: center;
            padding: var(--spacing-4);
        }

        .admin-sidebar .nav-link .badge {
            position: absolute;
            top: var(--spacing-2);
            right: var(--spacing-2);
            font-size: var(--font-size-xs);
            padding: var(--spacing-0-5) var(--spacing-1);
        }

        .admin-main-content {
            margin-left: 70px;
        }

        .content-wrapper {
            padding: var(--spacing-5);
        }

        .content-header {
            padding: var(--spacing-5);
            margin: calc(-1 * var(--spacing-5)) calc(-1 * var(--spacing-5)) var(--spacing-5) calc(-1 * var(--spacing-5));
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .admin-layout {
            position: relative;
        }

        .admin-sidebar {
            transform: translateX(-100%);
            width: 280px;
        }

        .admin-sidebar.mobile-open {
            transform: translateX(0);
        }

        .admin-sidebar .brand-text,
        .admin-sidebar .nav-text,
        .admin-sidebar .user-info {
            display: block;
        }

        .admin-sidebar .nav-link {
            justify-content: flex-start;
        }

        .admin-main-content {
            margin-left: 0;
        }

        .mobile-menu-toggle {
            position: fixed;
            top: var(--spacing-5);
            left: var(--spacing-5);
            z-index: var(--z-50);
            background-color: var(--color-primary-600);
            color: var(--color-white);
            width: var(--spacing-10);
            height: var(--spacing-10);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-md);
        }

        .content-wrapper {
            padding: var(--spacing-4);
        }

        .content-header {
            padding: var(--spacing-4);
            margin: calc(-1 * var(--spacing-4)) calc(-1 * var(--spacing-4)) var(--spacing-4) calc(-1 * var(--spacing-4));
        }

        .content-header .header-title {
            font-size: var(--font-size-xl);
        }
    }

    /* Grands écrans */
    @media (min-width: 1536px) {
        .content-wrapper {
            padding: var(--spacing-10);
            max-width: 1600px;
        }

        .content-header {
            padding: var(--spacing-8) var(--spacing-10);
            margin: calc(-1 * var(--spacing-10)) calc(-1 * var(--spacing-10)) var(--spacing-8) calc(-1 * var(--spacing-10));
        }
    }

    /* ===== MODE SOMBRE ===== */
    @media (prefers-color-scheme: dark) {
        .admin-layout {
            background-color: var(--color-secondary-900);
            color: var(--color-secondary-200);
        }

        .admin-main-content {
            background-color: var(--color-secondary-900);
        }

        .dashboard-card {
            background-color: var(--color-secondary-800);
            border-color: var(--color-secondary-700);
        }

        .content-header {
            background-color: var(--color-secondary-800);
            border-color: var(--color-secondary-700);
            color: var(--color-secondary-200);
        }

        .content-header .header-title {
            color: var(--color-secondary-100);
        }

        .content-header .header-subtitle {
            color: var(--color-secondary-400);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar mobile
        const createMobileToggle = () => {
            if (window.innerWidth <= 768) {
                if (!document.querySelector('.mobile-menu-toggle')) {
                    const toggleBtn = document.createElement('div');
                    toggleBtn.className = 'mobile-menu-toggle';
                    toggleBtn.innerHTML = '<i>☰</i>';
                    document.body.appendChild(toggleBtn);

                    toggleBtn.addEventListener('click', () => {
                        document.querySelector('.admin-sidebar').classList.toggle('mobile-open');
                    });

                    // Fermer en cliquant à l'extérieur
                    document.addEventListener('click', (e) => {
                        const sidebar = document.querySelector('.admin-sidebar');
                        const toggleBtn = document.querySelector('.mobile-menu-toggle');
                        if (sidebar && toggleBtn && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                            sidebar.classList.remove('mobile-open');
                        }
                    });
                }
            } else {
                const toggleBtn = document.querySelector('.mobile-menu-toggle');
                if (toggleBtn) {
                    toggleBtn.remove();
                }
            }
        };

        window.addEventListener('resize', createMobileToggle);
        createMobileToggle();

        // Gestion des liens actifs
        document.querySelectorAll('.admin-sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.querySelector('.admin-sidebar').classList.remove('mobile-open');
                }

                document.querySelectorAll('.admin-sidebar .nav-link').forEach(l => {
                    l.classList.remove('active');
                });

                this.classList.add('active');
            });
        });
    });
</script>