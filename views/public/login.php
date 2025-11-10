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

<style>
    .login-section {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .login-section h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }

    .login-hint {
        text-align: center;
        margin-top: 15px;
    }

    .flash-message {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 3px;
        text-align: center;
    }

    .flash-message.error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .flash-message.success {
        background-color: #d4edda;
        color: #155724;
    }
</style>