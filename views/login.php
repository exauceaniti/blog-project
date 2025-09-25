<?php
session_start();
// Stocker l'URL de redirection si fournie
if (isset($_GET['redirect'])) {
    $_SESSION['redirect_url'] = $_GET['redirect'];
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GraphicArt Blog</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Background animé -->
    <div class="auth-background">
        <div class="gradient-blob blob-1"></div>
        <div class="gradient-blob blob-2"></div>
        <div class="gradient-blob blob-3"></div>
    </div>

    <!-- Bouton thème -->
    <button class="theme-toggle-auth" onclick="toggleTheme()">
        <i class="fas fa-moon"></i>
    </button>

    <main>
        <div class="auth-container">
            <div class="auth-header">
                <h1 class="auth-title">
                    <i class="fas fa-sign-in-alt"></i>
                    Connexion
                </h1>
                <p class="auth-subtitle">Content de vous revoir ! Connectez-vous à votre compte</p>
            </div>

            <!-- Messages d'erreur/succès -->
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?= htmlspecialchars($_SESSION['error_message'] ?? 'Une erreur est survenue') ?></span>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['success_message'] ?? 'Action réussie') ?></span>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- Formulaire de connexion -->
            <form action="../controllers/UserController.php" method="POST" class="auth-form" id="loginForm">
                <input type="hidden" name="action" value="connexion">

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Adresse email
                    </label>
                    <input type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        required
                        placeholder="votre@email.com"
                        value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"
                        autocomplete="email">
                </div>

                <div class="form-group password-toggle">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mot de passe
                    </label>
                    <input type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        required
                        placeholder="Votre mot de passe"
                        autocomplete="current-password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="forgot-password.php" class="forgot-password">
                        <i class="fas fa-key"></i> Mot de passe oublié ?
                    </a>
                </div>

                <button type="submit" class="auth-button btn-primary" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Se connecter</span>
                </button>
            </form>

            <!-- Séparateur social -->
            <div class="social-login">
                <div class="social-divider">
                    <span>Ou continuer avec</span>
                </div>
                <div class="social-buttons">
                    <button type="button" class="social-btn google" onclick="socialLogin('google')">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                    <button type="button" class="social-btn github" onclick="socialLogin('github')">
                        <i class="fab fa-github"></i>
                        GitHub
                    </button>
                </div>
            </div>

            <!-- Lien d'inscription -->
            <div class="auth-links">
                <p>Pas encore de compte ?
                    <a href="register.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>"
                        class="auth-link">
                        <i class="fas fa-user-plus"></i> Créer un compte
                    </a>
                </p>
            </div>
        </div>
    </main>

    <?php unset($_SESSION['form_data']); ?>

    <script>
        // Gestion du thème
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Changer l'icône
            const icon = document.querySelector('.theme-toggle-auth i');
            icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        // Appliquer le thème sauvegardé
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);

            const icon = document.querySelector('.theme-toggle-auth i');
            icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

            // Animation d'entrée
            const container = document.querySelector('.auth-container');
            container.style.animation = 'slideIn 0.6s ease';
        });

        // Toggle visibilité mot de passe
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }

        // Simulation login social
        function socialLogin(provider) {
            const button = event.target.closest('.social-btn');
            const originalContent = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
            button.classList.add('loading');

            setTimeout(() => {
                alert(`Connexion avec ${provider} - Fonctionnalité à implémenter`);
                button.innerHTML = originalContent;
                button.classList.remove('loading');
            }, 1500);
        }

        // Gestion soumission formulaire
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalContent = submitBtn.innerHTML;

            // Animation de chargement
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
            submitBtn.classList.add('loading');

            // Simuler un délai pour voir l'animation (à retirer en production)
            setTimeout(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.classList.remove('loading');
            }, 2000);
        });

        // Validation en temps réel
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        emailInput.addEventListener('input', function() {
            if (this.validity.valid) {
                this.style.borderColor = 'var(--accent-3)';
            } else {
                this.style.borderColor = 'var(--danger)';
            }
        });

        passwordInput.addEventListener('input', function() {
            if (this.value.length >= 6) {
                this.style.borderColor = 'var(--accent-3)';
            } else {
                this.style.borderColor = 'var(--danger)';
            }
        });

        // Effets de focus avancés
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Raccourci clavier Entrée
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && document.activeElement.tagName !== 'TEXTAREA') {
                const submitBtn = document.getElementById('submitBtn');
                if (!submitBtn.classList.contains('loading')) {
                    document.getElementById('loginForm').requestSubmit();
                }
            }
        });
    </script>
</body>

</html>