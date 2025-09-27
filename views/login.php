<?php
session_start();

// Redirection si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: /admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | GraphiCart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <button class="theme-toggle" id="themeToggle" title="Changer le thème">
            <i class="fas fa-moon"></i>
        </button>

        <h1><i class="fas fa-sign-in-alt"></i> Connexion</h1>

        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="toast error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($_SESSION['error_message']) ?></span>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="toast success">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($_SESSION['success_message']) ?></span>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <form action="../controllers/UserController.php" method="POST" id="loginForm">
            <input type="hidden" name="action" value="connexion">

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required
                    value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? ($_COOKIE['remember_email'] ?? '')) ?>">
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                    <button type="button" class="toggle-password" id="togglePassword"><i class="fas fa-eye"></i></button>
                </div>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox" name="remember" <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>> Se souvenir de moi</label>
                <a href="forgot-password.php">Mot de passe oublié ?</a>
            </div>

            <button type="submit" id="submitBtn"><i class="fas fa-sign-in-alt"></i> Connexion</button>
        </form>

        <p class="register-link">
            Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>
        </p>
    </div>

    <script src="/assets/js/login.js"></script>
    <?php unset($_SESSION['form_data']); ?>
</body>

</html>