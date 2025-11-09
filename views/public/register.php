<!-- views/public/register.php -->

<section class="register-section">
    <h2>Créer un compte</h2>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-message <?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['message'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="/register">
        <div class="form-group">
            <label for="nom">Nom complet</label>
            <input type="text" name="nom" id="nom" required placeholder=" votre nom ">
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" name="email" id="email" required placeholder="ex: Exemple@gmail.com">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required placeholder="••••••••">
        </div>

        <button type="submit">S’inscrire</button>
    </form>

    <p class="register-hint">Déjà inscrit ? <a href="/login">Se connecter</a></p>
</section>
