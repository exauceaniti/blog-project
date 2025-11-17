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














<style>
    .register-section {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .register-section h2 {
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
</style>