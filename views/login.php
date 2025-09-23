<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/index.css">
</head>

<body>
    <main>
        <div class="login-container">
            <h1>Connexion</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?= $_SESSION['error_message'] ?? 'Erreur' ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="../controllers/UserController.php" method="POST">
                <input type="hidden" name="action" value="connexion">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required
                        value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Se connecter</button>
            </form>
            <p class="register-link">Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
        </div>
    </main>
    <?php unset($_SESSION['form_data']); ?>
</body>

</html>