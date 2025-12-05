<div class="l-content-wrapper">
    <div class="l-create-header">
        <h1>✍️ Nouvel Article</h1>
        <a href="/admin/post_manager" class="l-back-link">← Retour</a>
    </div>

    <form action="/post/create" method="POST" enctype="multipart/form-data" class="l-create-form">

        <div class="l-form-group">
            <label for="titre">Titre</label>
            <input
                type="text"
                id="titre"
                name="titre"
                class="l-input"
                placeholder="Titre de votre article"
                required
                value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
        </div>

        <div class="l-form-group">
            <label for="contenu">Contenu</label>
            <textarea
                id="contenu"
                name="contenu"
                class="l-textarea"
                placeholder="Écrivez votre article ici..."
                required><?= htmlspecialchars($_POST['contenu'] ?? '') ?></textarea>
        </div>

        <div class="l-form-group">
            <label for="media">Image en vedette</label>
            <div class="l-file-input-wrapper">
                <input
                    type="file"
                    id="media"
                    name="media"
                    class="l-file-input"
                    accept="image/*,video/mp4"
                    required>
                <div class="l-file-input-label">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span id="fileName">Choisir une image ou vidéo</span>
                </div>
            </div>
            <div class="l-file-preview" id="mediaPreview"></div>
        </div>

        <input type="hidden" name="auteur_id" value="<?= $_SESSION['user_id'] ?? 1 ?>">

        <div class="l-form-actions">
            <button type="submit" class="c-btn c-btn--primary">Publier</button>
        </div>
    </form>
</div>

<script>
    const fileInput = document.getElementById('media');
    const fileName = document.getElementById('fileName');
    const mediaPreview = document.getElementById('mediaPreview');

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

    // Drag and drop + click/keyboard open support
    const wrapper = document.querySelector('.l-file-input-wrapper');
    const labelEl = wrapper ? wrapper.querySelector('.l-file-input-label') : null;

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

    // Clicking the label area should open the file picker (input is hidden)
    if (labelEl) {
        labelEl.addEventListener('click', (e) => {
            // delegate to the hidden input
            fileInput.click();
        });

        // keyboard accessibility (Enter / Space)
        labelEl.setAttribute('role', 'button');
        labelEl.setAttribute('tabindex', '0');
        labelEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                fileInput.click();
            }
        });
    }
</script>