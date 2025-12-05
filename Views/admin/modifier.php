<?php if (!isset($article)): return;
endif; ?>

<div class="l-content-wrapper">
    <div class="l-create-header">
        <h1>✏️ Modifier l'Article</h1>
        <a href="/admin/post_manager" class="l-back-link">← Retour</a>
    </div>

    <form action="/post/update/<?= $article->id ?>" method="POST" enctype="multipart/form-data" class="l-create-form">
        <input type="hidden" name="_method" value="PUT">

        <div class="l-form-group">
            <label for="titre">Titre</label>
            <input
                type="text"
                id="titre"
                name="titre"
                class="l-input"
                placeholder="Titre de l'article"
                required
                value="<?= htmlspecialchars($_POST['titre'] ?? $article->titre ?? '') ?>">
        </div>

        <div class="l-form-group">
            <label for="contenu">Contenu</label>
            <textarea
                id="contenu"
                name="contenu"
                class="l-textarea"
                placeholder="Écrivez votre article ici..."
                required><?= htmlspecialchars($_POST['contenu'] ?? $article->contenu ?? '') ?></textarea>
        </div>

        <div class="l-form-group">
            <label for="media">Média (laisser vide pour conserver l'actuel)</label>
            <div class="l-file-input-wrapper">
                <input
                    type="file"
                    id="media"
                    name="media"
                    class="l-file-input"
                    accept="image/*,video/mp4">
                <div class="l-file-input-label">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span id="fileName">Choisir une image ou vidéo</span>
                </div>
            </div>
            <div class="l-file-preview" id="mediaPreview">
                <?php if (!empty($article->media_path)): ?>
                    <?php $ext = pathinfo($article->media_path, PATHINFO_EXTENSION); ?>
                    <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($article->media_path) ?>" alt="Media actuel" />
                    <?php else: ?>
                        <video controls>
                            <source src="/uploads/<?= htmlspecialchars($article->media_path) ?>" />
                        </video>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <input type="hidden" name="auteur_id" value="<?= $_SESSION['user_id'] ?? 1 ?>">

        <div class="l-form-actions">
            <button type="submit" class="c-btn c-btn--primary">Mettre à jour</button>
        </div>
    </form>
</div>

<script>
    (function() {
        const fileInput = document.getElementById('media');
        const fileName = document.getElementById('fileName');
        const mediaPreview = document.getElementById('mediaPreview');
        const wrapper = document.querySelector('.l-file-input-wrapper');
        const labelEl = wrapper ? wrapper.querySelector('.l-file-input-label') : null;

        if (!fileInput) return;

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(event) {
                    mediaPreview.innerHTML = '';

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        mediaPreview.appendChild(img);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = event.target.result;
                        video.controls = true;
                        mediaPreview.appendChild(video);
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        if (wrapper) {
            wrapper.addEventListener('dragover', (e) => {
                e.preventDefault();
                wrapper.classList.add('dragging');
            });

            wrapper.addEventListener('dragleave', () => {
                wrapper.classList.remove('dragging');
            });

            wrapper.addEventListener('drop', (e) => {
                e.preventDefault();
                wrapper.classList.remove('dragging');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    const event = new Event('change', {
                        bubbles: true
                    });
                    fileInput.dispatchEvent(event);
                }
            });
        }

        if (labelEl) {
            labelEl.addEventListener('click', () => fileInput.click());
            labelEl.setAttribute('role', 'button');
            labelEl.setAttribute('tabindex', '0');
            labelEl.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    fileInput.click();
                }
            });
        }
    })();
</script>