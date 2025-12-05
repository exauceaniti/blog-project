// public/js/carousel.js
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('latest-articles-carousel');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (!carousel || !prevBtn || !nextBtn) return;
    
    const cardWidth = document.querySelector('.article-card').offsetWidth + 16; // + gap
    const scrollAmount = cardWidth * 2; // Défile de 2 cartes à la fois
    
    prevBtn.addEventListener('click', () => {
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });
    
    nextBtn.addEventListener('click', () => {
        carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });
    
    // Gestion du survol pour afficher/masquer les boutons
    carousel.parentElement.addEventListener('mouseenter', () => {
        prevBtn.style.opacity = '1';
        nextBtn.style.opacity = '1';
    });
    
    carousel.parentElement.addEventListener('mouseleave', () => {
        prevBtn.style.opacity = '0.8';
        nextBtn.style.opacity = '0.8';
    });
});