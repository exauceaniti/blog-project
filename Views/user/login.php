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
<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: #f0f2f5;
        padding: 20px;
    }

    .auth-card {
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .auth-icon {
        font-size: 40px;
        color: #4a90e2;
        margin-bottom: 10px;
    }

    .auth-body {
        margin-bottom: 20px;
    }

    .alert {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .auth-form .form-group {
        position: relative;
        margin-bottom: 20px;
    }

    .floating-input {
        width: 100%;
        padding: 12px 40px 12px 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
        transition: border-color 0.3s;
    }

    .floating-input.error {
        border-color: #e74c3c;
    }

    .floating-label {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        background: #ffffff;
        padding: 0 5px;
        color: #999;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .floating-input:focus+.floating-label,
    .floating-input:not(:placeholder-shown)+.floating-label {
        top: -10px;
        left: 8px;
        font-size: 12px;
        color: #4a90e2;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #999;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 14px;
        color: #555;
    }

    .checkbox-wrapper input {
        margin-right: 8px;
    }

    .forgot-link {
        font-size: 14px;
        color: #4a90e2;
        text-decoration: none;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .btn-full {
        width: 100%;
    }

    .form-global-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .form-global-error i {
        margin-right: 8px;
    }

    .field-error {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .field-error i {
        margin-right: 5px;
    }
</style>