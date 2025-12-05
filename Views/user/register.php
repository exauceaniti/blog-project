<?php

/**
 * Vue d'inscription de l'utilisateur - Version améliorée
 */
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2>Créer un compte</h2>
            <p>Rejoignez notre communauté</p>
        </div>

        <div class="auth-body">
            <?php if (isset($flash['success'])) : ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= $flash['success'] ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" class="auth-form">
                <?php if (isset($errors['global'])) : ?>
                    <div class="form-global-error">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <?= $errors['global'] ?>
                    </div>
                <?php endif; ?>

                <div class="form-group floating-group">
                    <input type="text"
                        id="register-nom"
                        name="nom"
                        class="floating-input <?= isset($errors['nom']) ? 'error' : '' ?>"
                        placeholder=" "
                        value="<?= htmlspecialchars($old['nom'] ?? '') ?>"
                        required>
                    <label for="register-nom" class="floating-label">
                        <i class="fa-solid fa-user"></i>
                        Nom complet
                    </label>
                    <?php if (isset($errors['nom'])) : ?>
                        <span class="field-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= $errors['nom'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="form-group floating-group">
                    <input type="email"
                        id="register-email"
                        name="email"
                        class="floating-input <?= isset($errors['email']) ? 'error' : '' ?>"
                        placeholder=" "
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        required>
                    <label for="register-email" class="floating-label">
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
                        id="register-password"
                        name="password"
                        class="floating-input <?= isset($errors['password']) ? 'error' : '' ?>"
                        placeholder=" "
                        required>
                    <label for="register-password" class="floating-label">
                        <i class="fa-solid fa-key"></i>
                        Mot de passe
                    </label>
                    <button type="button" class="password-toggle" aria-label="Afficher le mot de passe">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" data-strength="0"></div>
                        </div>
                        <span class="strength-text">Faible</span>
                    </div>
                    <?php if (isset($errors['password'])) : ?>
                        <span class="field-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= $errors['password'] ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- <div class="form-group floating-group">
                    <input type="password"
                        id="register-password-confirm"
                        name="password_confirm"
                        class="floating-input"
                        placeholder=" "
                        required>
                    <label for="register-password-confirm" class="floating-label">
                        <i class="fa-solid fa-key"></i>
                        Confirmer le mot de passe
                    </label>
                    <button type="button" class="password-toggle" aria-label="Afficher le mot de passe">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div> -->

                <div class="form-terms">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        J'accepte les
                        <a href="/terms" class="terms-link">conditions d'utilisation</a>
                        et la
                        <a href="/privacy" class="terms-link">politique de confidentialité</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-large btn-full">
                    <i class="fa-solid fa-user-plus"></i>
                    Créer mon compte
                </button>
            </form>
        </div>

        <div class="auth-footer">
            <p>Déjà un compte ?
                <a href="/login" class="auth-link">Se connecter</a>
            </p>
        </div>
    </div>
</div>

<style>

</style>