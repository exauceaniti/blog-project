<!-- Views/user/login.php -->
<div class="auth-layout">
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-feather-alt"></i>
                MonBlog
            </div>
            <h1 class="auth-title">Connexion à votre compte</h1>
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
            <form action="/login" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email" class="form-label required">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                        autofocus
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
                        placeholder="Votre mot de passe">
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input" value="1">
                        <label for="remember" class="form-check-label">Se souvenir de moi</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-full">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </button>
            </form>

            <!-- Liens supplémentaires -->
            <div class="auth-links">
                <a href="/forgot-password" class="auth-link">
                    <i class="fas fa-key"></i>
                    Mot de passe oublié ?
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>Pas encore de compte ?
                <a href="/register" class="auth-link">Créer un compte</a>
            </p>
        </div>
    </div>
</div>