// assets/js/admin.js
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du thème
    const themeToggle = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
    
    themeToggle.addEventListener('click', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
    
    function updateThemeIcon(theme) {
        const icon = themeToggle.querySelector('i');
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
    
    // Sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        const icon = sidebarToggle.querySelector('i');
        icon.className = sidebar.classList.contains('collapsed') ? 
            'fas fa-angle-double-right' : 'fas fa-angle-double-left';
    });
    
    // Gestion des formulaires
    const toggleAddForm = document.getElementById('toggleAddForm');
    const addForm = document.getElementById('addForm');
    
    if (toggleAddForm && addForm) {
        toggleAddForm.addEventListener('click', function() {
            const isVisible = addForm.style.display === 'block';
            addForm.style.display = isVisible ? 'none' : 'block';
            toggleAddForm.textContent = isVisible ? 'Afficher le formulaire' : 'Masquer le formulaire';
        });
    }
    
    // Auto-hide toast
    const toast = document.getElementById('toast');
    if (toast) {
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
});

// Fonctions globales
function toggleEditForm(articleId) {
    const form = document.getElementById(`edit-form-${articleId}`);
    form.classList.toggle('active');
}

function toggleArticleContent(articleId) {
    const excerpt = document.querySelector(`[data-article-id="${articleId}"] .article-excerpt`);
    const fullContent = document.querySelector(`[data-article-id="${articleId}"] .article-full-content`);
    
    if (excerpt && fullContent) {
        const isVisible = fullContent.style.display === 'block';
        excerpt.style.display = isVisible ? '-webkit-box' : 'none';
        fullContent.style.display = isVisible ? 'none' : 'block';
    }
}

function filterArticles(searchTerm) {
    const articles = document.querySelectorAll('.article-card');
    const term = searchTerm.toLowerCase();
    
    articles.forEach(article => {
        const title = article.getAttribute('data-title');
        const isVisible = title.includes(term);
        article.style.display = isVisible ? 'block' : 'none';
    });
}

function previewMedia(input) {
    const preview = document.getElementById('mediaPreview');
    const previewInner = document.getElementById('previewInner');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewInner.innerHTML = '';
            
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '200px';
                previewInner.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = e.target.result;
                video.controls = true;
                video.style.maxWidth = '100%';
                video.style.maxHeight = '200px';
                previewInner.appendChild(video);
            } else {
                previewInner.innerHTML = `<div class="audio-placeholder"><i class="fas fa-music"></i> Fichier audio</div>`;
            }
            
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
    }
}

function clearPreview() {
    const preview = document.getElementById('mediaPreview');
    const input = document.getElementById('mediaInput');
    
    preview.style.display = 'none';
    preview.innerHTML = '<div id="previewInner"></div>';
    if (input) input.value = '';
}

function toggleZoom(mediaSrc) {
    const modal = document.getElementById('modalOverlay');
    const modalInner = document.getElementById('modalInner');
    
    modalInner.innerHTML = '';
    
    if (mediaSrc.endsWith('.mp4') || mediaSrc.endsWith('.webm') || mediaSrc.includes('video')) {
        const video = document.createElement('video');
        video.src = mediaSrc;
        video.controls = true;
        video.autoplay = true;
        video.style.maxWidth = '100%';
        video.style.maxHeight = '80vh';
        modalInner.appendChild(video);
    } else {
        const img = document.createElement('img');
        img.src = mediaSrc;
        img.style.maxWidth = '100%';
        img.style.maxHeight = '80vh';
        img.style.objectFit = 'contain';
        modalInner.appendChild(img);
    }
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('modalOverlay');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
    
    // Pause video when closing
    const video = modal.querySelector('video');
    if (video) {
        video.pause();
        video.currentTime = 0;
    }
}

// Fermer modal avec ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Fermer modal en cliquant à l'extérieur
document.getElementById('modalOverlay')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Aperçu complet d'article en modale
function showArticleModal(id, titre, contenu, mediaType, mediaPath) {
    const modal = document.getElementById('articleModal');
    const modalContent = document.getElementById('articleModalContent');
    let html = `<h3>${titre}</h3><div style="margin-bottom:1em;">${contenu.replace(/\n/g, '<br>')}</div>`;
    if (mediaPath) {
        if (mediaType === 'image') {
            html += `<img src="${mediaPath}" alt="media" style="max-width:100%;margin-bottom:1em;">`;
        } else if (mediaType === 'video') {
            html += `<video src="${mediaPath}" controls style="max-width:100%;margin-bottom:1em;"></video>`;
        } else if (mediaType === 'audio') {
            html += `<audio src="${mediaPath}" controls style="width:100%;margin-bottom:1em;"></audio>`;
        }
    }
    html += `<button class="btn btn-secondary" onclick="closeArticleModal()" style="float:right;">Fermer</button>`;
    modalContent.innerHTML = html;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeArticleModal() {
    const modal = document.getElementById('articleModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fermer la modale article avec ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeArticleModal();
});

// Fermer la modale article en cliquant à l'extérieur
document.getElementById('articleModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeArticleModal();
});