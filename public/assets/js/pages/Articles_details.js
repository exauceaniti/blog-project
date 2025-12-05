
    // Script pour le compteur de caractères
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('contenu_commentaire');
        const charCount = document.querySelector('.char-count');

        if (textarea && charCount) {
            textarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = `${length}/1000 caractères`;

                // Changement de couleur selon la longueur
                if (length > 900) {
                    charCount.style.color = '#ff4757';
                } else if (length > 700) {
                    charCount.style.color = '#ffa502';
                } else {
                    charCount.style.color = '#666';
                }
            });

            // Initialiser le compteur
            textarea.dispatchEvent(new Event('input'));
        }

        // Smooth scroll vers les commentaires après soumission
        if (window.location.hash === '#comments') {
            const commentsSection = document.querySelector('.comments-section');
            if (commentsSection) {
                setTimeout(() => {
                    commentsSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                }, 100);
            }
        }
    });