<!DOCTYPE html>
<html lang="fr">

<head>
    <?php \Src\Core\Render\Fragment::meta($page_title ?? 'Admin - Dashboard'); ?>
</head>

<body class="l-admin-layout">
    <aside class="l-admin-sidebar">
        <?php \Src\Core\Render\Fragment::sidebar($sidebar_params ?? []); ?>
    </aside>

    <main class="l-admin-main-content">
        <div class="l-content-wrapper">
            <?= $page_view ?>
        </div>
    </main>
</body>

</html>

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