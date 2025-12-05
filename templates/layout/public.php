<!DOCTYPE html>
<html lang="fr">

<head>
    <?php \Src\Core\Render\Fragment::meta($page_title ?? 'Mon blog-Exau'); ?>

</head>

<body class="public-layout">
    <?php \Src\Core\Render\Fragment::header(); ?>

    <div class="content-wrapper">
        <main class="public-main">
            <?= $page_view ?>
        </main>
    </div>

    <?php \Src\Core\Render\Fragment::footer(); ?>
</body>

</html>

<style>
    /* ===== LAYOUT PRINCIPAL ===== */
    .public-layout {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* ===== HEADER ===== */
    .public-header {
        background-color: var(--color-white);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 0;
        z-index: var(--z-40);
        border-bottom: var(--border-width-1) solid var(--color-secondary-200);
    }

    .header-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 var(--spacing-8);
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Logo */
    .site-logo {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        text-decoration: none;
        font-weight: var(--font-weight-bold);
        font-size: var(--font-size-2xl);
        color: var(--color-primary-600);
    }

    .logo-icon {
        font-size: var(--font-size-3xl);
        color: var(--color-primary-600);
    }

    .logo-text {
        font-family: var(--font-family-serif);
    }

    /* Navigation principale */
    .main-nav ul {
        display: flex;
        list-style: none;
        gap: var(--spacing-8);
    }

    .nav-link {
        text-decoration: none;
        color: var(--color-secondary-800);
        font-weight: var(--font-weight-medium);
        padding: var(--spacing-2) 0;
        position: relative;
        transition: var(--transition-normal);
    }

    .nav-link:hover {
        color: var(--color-primary-600);
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: var(--border-width-2);
        background-color: var(--color-primary-600);
        transition: width var(--transition-normal);
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .nav-link.active {
        color: var(--color-primary-600);
    }

    .nav-link.active::after {
        width: 100%;
    }

    /* Bouton CTA */
    .cta-button {
        background-color: var(--color-primary-600);
        color: var(--color-white);
        padding: var(--spacing-3) var(--spacing-6);
        border-radius: var(--radius-md);
        text-decoration: none;
        font-weight: var(--font-weight-semibold);
        transition: var(--transition-normal);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-2);
    }

    .cta-button:hover {
        background-color: var(--color-primary-700);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ===== CONTENU PRINCIPAL ===== */
    .content-wrapper {
        flex: 1;
        max-width: 1280px;
        margin: 0 auto;
        padding: var(--spacing-8);
        width: 100%;
    }

    .public-main {
        min-height: calc(100vh - 80px - 200px);
    }

    /* ===== FOOTER ===== */
    .public-footer {
        background-color: var(--color-secondary-900);
        color: var(--color-white);
        padding: var(--spacing-16) 0 var(--spacing-8);
        margin-top: auto;
    }

    .footer-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 var(--spacing-8);
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-12);
        margin-bottom: var(--spacing-12);
    }

    /* Colonnes du footer */
    .footer-col h3 {
        color: var(--color-white);
        font-size: var(--font-size-lg);
        margin-bottom: var(--spacing-6);
        position: relative;
        padding-bottom: var(--spacing-3);
    }

    .footer-col h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: var(--spacing-10);
        height: var(--border-width-2);
        background-color: var(--color-primary-600);
    }

    .footer-col ul {
        list-style: none;
    }

    .footer-col ul li {
        margin-bottom: var(--spacing-3);
    }

    .footer-col a {
        color: var(--color-secondary-300);
        text-decoration: none;
        transition: var(--transition-normal);
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-2);
    }

    .footer-col a:hover {
        color: var(--color-white);
        transform: translateX(var(--spacing-1));
    }

    /* Social links */
    .social-links {
        display: flex;
        gap: var(--spacing-4);
        margin-top: var(--spacing-4);
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: var(--spacing-9);
        height: var(--spacing-9);
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: var(--radius-full);
        color: var(--color-white);
        text-decoration: none;
        transition: var(--transition-normal);
    }

    .social-link:hover {
        background-color: var(--color-primary-600);
        transform: translateY(-3px);
    }

    /* Copyright */
    .footer-bottom {
        border-top: var(--border-width-1) solid rgba(255, 255, 255, 0.1);
        padding-top: var(--spacing-8);
        text-align: center;
        color: var(--color-secondary-400);
        font-size: var(--font-size-sm);
    }

    /* ===== COMPOSANTS RÉUTILISABLES ===== */

    /* Cartes */
    .card {
        background-color: var(--color-white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        transition: var(--transition-normal);
        border: var(--border-width-1) solid var(--color-secondary-200);
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
    }

    .card-header {
        padding: var(--spacing-6);
        border-bottom: var(--border-width-1) solid var(--color-secondary-200);
    }

    .card-body {
        padding: var(--spacing-6);
    }

    .card-footer {
        padding: var(--spacing-4) var(--spacing-6);
        background-color: var(--color-secondary-50);
        border-top: var(--border-width-1) solid var(--color-secondary-200);
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: var(--spacing-1) var(--spacing-3);
        border-radius: var(--radius-full);
        font-size: var(--font-size-xs);
        font-weight: var(--font-weight-semibold);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-primary {
        background-color: var(--color-primary-100);
        color: var(--color-primary-800);
    }

    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    /* Alertes */
    .alert {
        padding: var(--spacing-4) var(--spacing-6);
        border-radius: var(--radius-md);
        margin-bottom: var(--spacing-6);
        border-left: var(--border-width-4) solid;
    }

    .alert-info {
        background-color: var(--color-primary-50);
        border-color: var(--color-primary-600);
        color: var(--color-primary-800);
    }

    .alert-success {
        background-color: #d1fae5;
        border-color: #10b981;
        color: #065f46;
    }

    .alert-warning {
        background-color: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }

    .alert-danger {
        background-color: #fee2e2;
        border-color: #ef4444;
        color: #991b1b;
    }

    /* ===== RESPONSIVE DESIGN ===== */

    /* Tablette */
    @media (max-width: 1024px) {
        .header-container {
            padding: 0 var(--spacing-6);
        }

        .content-wrapper {
            padding: var(--spacing-6);
        }

        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-8);
        }

        .main-nav ul {
            gap: var(--spacing-6);
        }

        .footer-container {
            padding: 0 var(--spacing-6);
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            height: auto;
            padding: var(--spacing-4);
            gap: var(--spacing-4);
        }

        .main-nav ul {
            flex-wrap: wrap;
            justify-content: center;
            gap: var(--spacing-4);
        }

        .content-wrapper {
            padding: var(--spacing-4);
        }

        .public-main {
            padding-top: var(--spacing-4);
        }

        .footer-grid {
            grid-template-columns: 1fr;
            gap: var(--spacing-8);
        }

        .footer-container {
            padding: 0 var(--spacing-4);
        }

        /* Menu mobile */
        .mobile-menu-toggle {
            display: none;
        }

        @media (max-width: 640px) {
            .mobile-menu-toggle {
                display: block;
                background: none;
                border: none;
                font-size: var(--font-size-2xl);
                color: var(--color-secondary-800);
                cursor: pointer;
            }

            .main-nav {
                display: none;
                width: 100%;
            }

            .main-nav.active {
                display: block;
            }

            .main-nav ul {
                flex-direction: column;
                align-items: center;
                padding-top: var(--spacing-4);
            }

            .header-container {
                flex-direction: row;
                justify-content: space-between;
            }
        }
    }

    /* Grands écrans */
    @media (min-width: 1536px) {

        .content-wrapper,
        .header-container,
        .footer-container {
            max-width: 1400px;
        }
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .public-main>* {
        animation: fadeInUp var(--transition-normal) ease-out;
    }

    /* ===== PAGES SPÉCIFIQUES ===== */

    /* Page d'accueil */
    .hero-section {
        text-align: center;
        padding: var(--spacing-16) 0;
        background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-info-500) 100%);
        color: var(--color-white);
        border-radius: var(--radius-3xl);
        margin-bottom: var(--spacing-12);
    }

    .hero-title {
        font-size: var(--font-size-5xl);
        font-weight: var(--font-weight-bold);
        margin-bottom: var(--spacing-4);
        font-family: var(--font-family-serif);
    }

    .hero-subtitle {
        font-size: var(--font-size-xl);
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto var(--spacing-8);
    }

    /* Grille d'articles */
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--spacing-8);
        margin-top: var(--spacing-8);
    }

    .article-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .article-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        color: var(--color-secondary-500);
        font-size: var(--font-size-sm);
        margin-top: auto;
        padding-top: var(--spacing-4);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menu mobile
        const toggleBtn = document.querySelector('.mobile-menu-toggle');
        const mainNav = document.querySelector('.main-nav');

        if (toggleBtn && mainNav) {
            toggleBtn.addEventListener('click', function() {
                mainNav.classList.toggle('active');
                this.innerHTML = mainNav.classList.contains('active') ?
                    '<i class="fas fa-times"></i>' :
                    '<i class="fas fa-bars"></i>';
            });

            // Fermer le menu en cliquant sur un lien
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 640 px) {
                        mainNav.classList.remove('active');
                        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                });
            });
        }

        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observer les cartes
        document.querySelectorAll('.card').forEach(card => {
            observer.observe(card);
        });

        // Gestion des liens actifs
        const currentPage = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    });
</script>