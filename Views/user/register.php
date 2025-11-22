<!-- Views/user/register.php -->
<div class="auth-layout">
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-feather-alt"></i>
                MonBlog
            </div>
            <h1 class="auth-title">Rejoignez notre communauté</h1>
        </div>

        <!-- Body -->
        <!-- Arrangement pour les messages flash -->
        <div class="auth-body">
            <?php if ($flash['hasError']): ?>
                <div class="alert alert-error">
                    <div class="alert-content">
                        <div class="alert-icon">⚠️</div>
                        <div class="alert-text">
                            <div class="alert-message"><?= $flash['error'] ?></div>
                        </div>
                        <button class="alert-close">&times;</button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($flash['hasSuccess']): ?>
                <div class="alert alert-success">
                    <div class="alert-content">
                        <div class="alert-icon">✓</div>
                        <div class="alert-text">
                            <div class="alert-message"><?= $flash['success'] ?></div>
                        </div>
                        <button class="alert-close">&times;</button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <form action="/register" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username" class="form-label required">Nom d'utilisateur</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        required
                        autofocus
                        placeholder="Votre nom d'utilisateur">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label required">Adresse email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                        placeholder="votre@email.com">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label required">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required
                        placeholder="Minimum 8 caractères">
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Le mot de passe doit contenir au moins 8 caractères
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm" class="form-label required">Confirmation du mot de passe</label>
                    <input
                        type="password"
                        id="password_confirm"
                        name="password_confirm"
                        class="form-control"
                        required
                        placeholder="Retapez votre mot de passe">
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            class="form-check-input"
                            value="1"
                            required>
                        <label for="terms" class="form-check-label">
                            J'accepte les
                            <a href="/terms" target="_blank" class="auth-link">conditions d'utilisation</a>
                            et la
                            <a href="/privacy" target="_blank" class="auth-link">politique de confidentialité</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-full">
                    <i class="fas fa-user-plus"></i>
                    Créer mon compte
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>Déjà un compte ?
                <a href="/login" class="auth-link">Se connecter</a>
            </p>
        </div>
    </div>
</div>