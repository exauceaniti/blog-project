<?php

/**
 * Vue de connexion de l'utilisateur - Version améliorée
 */
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2>Connexion</h2>
            <p>Accédez à votre espace personnel</p>
        </div>

        <div class="auth-body">
            <?php if (isset($flash['success'])) : ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= $flash['success'] ?></span>
                </div>
            <?php elseif (isset($flash['error'])) : ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= $flash['error'] ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login" class="auth-form">
                <?php if (isset($errors['global'])) : ?>
                    <div class="form-global-error">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <?= $errors['global'] ?>
                    </div>
                <?php endif; ?>

                <div class="form-group floating-group">
                    <input type="email"
                        id="login-email"
                        name="email"
                        class="floating-input <?= isset($errors['email']) ? 'error' : '' ?>"
                        placeholder=" "
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        required>
                    <label for="login-email" class="floating-label">
                        <i class="fa-solid fa-envelope"></i>
                        Adresse email
                    </label>
                    <?php if (isset($errors['email'])) : ?>
                        <span class="field-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= $errors['email'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="form-group floating-group">
                    <input type="password"
                        id="login-password"
                        name="password"
                        class="floating-input <?= isset($errors['password']) ? 'error' : '' ?>"
                        placeholder=" "
                        required>
                    <label for="login-password" class="floating-label">
                        <i class="fa-solid fa-lock"></i>
                        Mot de passe
                    </label>
                    <button type="button" class="password-toggle" aria-label="Afficher le mot de passe">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <?php if (isset($errors['password'])) : ?>
                        <span class="field-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= $errors['password'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Se souvenir de moi
                    </label>
                    <a href="/forgot-password" class="forgot-link">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-large btn-full">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Se connecter
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <p>Pas encore de compte ?
                <a href="/register" class="auth-link">Créer un compte</a>
            </p>
        </div>
    </div>
</div>