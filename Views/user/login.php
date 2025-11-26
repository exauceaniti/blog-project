<div class="form-container">
    <h2>Connexion</h2>

    <?php if (isset($flash['success'])): ?>
        <p class="flash-success"><?= $flash['success'] ?></p>
    <?php elseif (isset($flash['error'])): ?>
        <p class="flash-error"><?= $flash['error'] ?></p>
    <?php endif; ?>

    <form method="POST" action="/login"> <?php if (isset($errors['global'])): ?>
            <p class="error-message-global"><?= $errors['global'] ?></p>
        <?php endif; ?>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                required>
            <?php if (isset($errors['email'])): ?>
                <span class="error-message-field"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password"
                id="password"
                name="password"
                value=""
                required>
            <?php if (isset($errors['password'])): ?>
                <span class="error-message-field"><?= $errors['password'] ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <p>Pas encore de compte ? <a href="/register">Inscrivez-vous</a></p>
</div>