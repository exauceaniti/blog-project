/**
 * Menue mobile toggle et effets de scroll pour le header
 */

  document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMain = document.querySelector('.nav-main');
  
    if (mobileMenuBtn && navMain) {
      mobileMenuBtn.addEventListener('click', function () {
        this.classList.toggle('active');
        navMain.classList.toggle('active');
        document.body.style.overflow = navMain.classList.contains('active') ? 'hidden' : '';
      });
    
      // Fermer le menu en cliquant à l'extérieur
      document.addEventListener('click', function (e) {
        if (!navMain.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
          mobileMenuBtn.classList.remove('active');
          navMain.classList.remove('active');
          document.body.style.overflow = '';
        }
      });
    }
  
    // Header scroll effect
    const header = document.querySelector('.site-header');
    if (header) {
      window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
      });
    }
  });
