<!-- views/public/login.php -->

<section class="login-section">
    <h2>Connexion</h2>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-message <?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['message'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="/login">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" name="email" id="email" required placeholder="ex: Exemple@gmail.com">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required placeholder="••••••••">
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <p class="login-hint">Pas encore inscrit ? <a href="/register">Créer un compte</a></p>
</section>
