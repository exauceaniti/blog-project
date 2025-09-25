// admin.js

// Toggle add form
document.addEventListener('DOMContentLoaded', function(){
    const addBtn = document.getElementById('toggleAddForm');
    if(addBtn){
        addBtn.addEventListener('click', function(){
            const addForm = document.getElementById('addForm');
            addForm.style.display = (addForm.style.display === 'none' || addForm.style.display === '') ? 'block' : 'none';
        });
    }

    // Sidebar toggle
    const sbToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if(sbToggle && sidebar){
        sbToggle.addEventListener('click', function(){
            sidebar.classList.toggle('collapsed');
            // change icon
            sbToggle.querySelector('i').classList.toggle('fa-angle-double-right');
        });
    }

    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    if(themeToggle){
        themeToggle.addEventListener('click', function(){
            const root = document.documentElement;
            const cur = root.getAttribute('data-theme') || 'light';
            const next = cur === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('adminTheme', next);
            themeToggle.querySelector('i').className = next === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });
    }

    // apply saved theme
    const saved = localStorage.getItem('adminTheme');
    if(saved) document.documentElement.setAttribute('data-theme', saved);

    // hide toasts after few seconds
    const toast = document.getElementById('toast');
    if(toast){
        setTimeout(()=> {
            toast.classList.add('fadeout');
            setTimeout(()=> toast.remove(), 400);
        }, 3000);
    }
});

// Preview media before upload
function previewMedia(input){
    const preview = document.getElementById('mediaPreview');
    const inner = document.getElementById('previewInner');
    if(input.files && input.files[0]){
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e){
            if(file.type.startsWith('image/')){
                inner.innerHTML = `<img src="${e.target.result}" style="max-width:320px;border-radius:8px">`;
            } else if(file.type.startsWith('video/')){
                inner.innerHTML = `<video controls src="${e.target.result}" style="max-width:320px;border-radius:8px"></video>`;
            } else if(file.type.startsWith('audio/')){
                inner.innerHTML = `<audio controls src="${e.target.result}"></audio>`;
            } else {
                inner.innerHTML = `<div>Fichier: ${file.name}</div>`;
            }
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function clearPreview(){
    const preview = document.getElementById('mediaPreview');
    const inner = document.getElementById('previewInner');
    const input = document.getElementById('mediaInput');
    if(input) input.value = '';
    if(inner) inner.innerHTML = '';
    if(preview) preview.style.display = 'none';
}

// Toggle edit form
function toggleEditForm(id){
    const el = document.getElementById('edit-form-' + id);
    if(!el) return;
    el.classList.toggle('active');
    el.scrollIntoView({behavior:'smooth', block:'center'});
}

// Toggle show full content (simple)
function toggleArticleContent(id){
    const card = document.querySelector(`[data-article-id='${id}']`);
    if(!card) return;
    const excerpt = card.querySelector('.article-excerpt');
    const full = card.dataset.full || null;
    if(!full){
        // fetch full content? We already have it in the hidden form textarea - as fallback just expand
        const textarea = card.querySelector('textarea');
        if(textarea){
            excerpt.innerHTML = textarea.value.replace(/\n/g, '<br>');
        } else {
            // fallback: remove ellipsis
            excerpt.innerHTML = excerpt.innerHTML.replace('...', '');
        }
        card.dataset.full = excerpt.innerHTML;
    } else {
        excerpt.innerHTML = full;
        delete card.dataset.full;
    }
}

// Filter articles client-side
function filterArticles(q){
    q = (q || '').toLowerCase();
    const cards = document.querySelectorAll('.article-card');
    cards.forEach(c => {
        const title = c.getAttribute('data-title') || '';
        c.style.display = title.includes(q) ? '' : 'none';
    });
}

// Modal zoom handling (receives full media path)
function toggleZoom(mediaSrc){
    const overlay = document.getElementById('modalOverlay');
    const inner = document.getElementById('modalInner');
    if(!overlay || !inner) return;
    inner.innerHTML = '';

    if(/\.(jpe?g|png|webp|gif)$/i.test(mediaSrc) || mediaSrc.includes('image/')){
        inner.innerHTML = `<img src="${mediaSrc}" style="max-width:100%;max-height:80vh;border-radius:8px">`;
    } else if(/\.(mp4|webm|mov|mkv)$/i.test(mediaSrc) || mediaSrc.includes('video/')){
        inner.innerHTML = `<video controls src="${mediaSrc}" style="width:100%;max-height:80vh;border-radius:8px"></video>`;
    } else if(/\.(mp3|wav|ogg)$/i.test(mediaSrc) || mediaSrc.includes('audio/')){
        inner.innerHTML = `<audio controls src="${mediaSrc}"></audio>`;
    } else {
        inner.innerHTML = `<div>Impossible d'afficher ce média</div>`;
    }

    overlay.classList.add('active');
}

function closeModal(){
    const overlay = document.getElementById('modalOverlay');
    if(overlay) overlay.classList.remove('active');
}
