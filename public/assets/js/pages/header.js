
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