/**
 * Code java script pour le sidebar de la page admin
 */

// Sidebar Admin Mobile
function initAdminSidebar() {
  const sidebar = document.querySelector('.admin-sidebar');
  const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
  const overlay = document.querySelector('.sidebar-overlay');
  
  if (mobileMenuBtn && sidebar) {
    mobileMenuBtn.addEventListener('click', function() {
      sidebar.classList.toggle('mobile-open');
    });
  }
  
  if (overlay) {
    overlay.addEventListener('click', function() {
      sidebar.classList.remove('mobile-open');
    });
  }
  
  // Fermer le sidebar en cliquant sur un lien (mobile)
  const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 1024) {
        sidebar.classList.remove('mobile-open');
      }
    });
  });
}

// Ajouter à votre DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
  initAdminSidebar();
  // ... vos autres initialisations
});
