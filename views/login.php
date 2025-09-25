<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | GraphiCart</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <main>
        <div class="login-container">
            <button class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>

            <h1><i class="fas fa-sign-in-alt"></i> Connexion</h1>

            <?php if (isset($_GET['error'])): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $_SESSION['error_message'] ?? 'Erreur' ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="../controllers/UserController.php" method="POST">
                <input type="hidden" name="action" value="connexion">

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <div class="input-container">
                        <input type="email" id="email" name="email" required
                            value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"
                            placeholder="votre@email.com">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Mot de passe:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required
                            placeholder="Votre mot de passe">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember"
                            <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>>
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Mot de passe oublié ?</a>
                </div>

                <button type="submit">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>

            <p class="register-link">Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
        </div>
    </main>

    <script src="../assets/js/login.js"></script>

    <?php unset($_SESSION['form_data']); ?>
</body>

</html>