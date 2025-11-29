/**
 * public/js/carousel.js
 * Gère le défilement horizontal du carrousel d'articles sur la page d'accueil.
 */

document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('latest-articles-carousel');
    // Vérifie si le carrousel existe avant d'essayer de le manipuler
    if (!carousel) return; 

    const prevBtn = document.querySelector('.carousel-btn.prev-btn');
    const nextBtn = document.querySelector('.carousel-btn.next-btn');
    
    // Fonction pour défiler
    const scrollCarousel = (direction) => {
        // Détermine la largeur d'une carte + l'espace entre elles (ajustez si votre CSS change)
        const scrollAmount = 320; // Exemple: Largeur de carte (300px) + Gap (20px)

        if (direction === 'next') {
            carousel.scrollLeft += scrollAmount;
        } else if (direction === 'prev') {
            carousel.scrollLeft -= scrollAmount;
        }
    };

    // Événements des boutons de navigation
    if (prevBtn) {
        prevBtn.addEventListener('click', () => scrollCarousel('prev'));
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => scrollCarousel('next'));
    }

    // Optionnel: masquer/afficher les boutons si vous êtes au début/fin (plus avancé)
    const updateButtonsVisibility = () => {
        if (prevBtn) {
            prevBtn.style.display = carousel.scrollLeft > 0 ? 'block' : 'none';
        }
        if (nextBtn) {
            // Afficher le bouton tant que la fin n'est pas atteinte
            const maxScroll = carousel.scrollWidth - carousel.clientWidth;
            nextBtn.style.display = carousel.scrollLeft < maxScroll ? 'block' : 'none';
        }
    };

    // Initialisation et écoute du défilement
    carousel.addEventListener('scroll', updateButtonsVisibility);
    updateButtonsVisibility(); // Appel initial
});