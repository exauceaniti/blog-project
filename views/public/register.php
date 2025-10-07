<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirection si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: /admin/dashboard.php");
    exit;
}

// Messages & données précédentes
$errorMessage = $_SESSION['error_message'] ?? null;
$successMessage = $_SESSION['success_message'] ?? null;
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['error_message'], $_SESSION['success_message'], $_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | GraphiCart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/register.css">
</head>

<body>
    <div class="register-container">

        <!-- Toggle thème -->
        <button class="theme-toggle" id="themeToggle" aria-label="Changer le thème">
            <i class="fas fa-moon"></i>
        </button>

        <h2><i class="fas fa-user-plus"></i> Inscription</h2>

        <!-- Messages -->
        <?php if ($errorMessage): ?>
            <div class="error-message"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="success-message"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form id="registerForm" action="../controllers/UserController.php" method="POST">
            <input type="hidden" name="action" value="inscription">

            <!-- Nom complet -->
            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom complet</label>
                <div class="input-container">
                    <input type="text" id="nom" name="nom" placeholder="Votre nom complet" required
                        value="<?= htmlspecialchars($formData['nom'] ?? '') ?>">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <div class="input-container">
                    <input type="email" id="email" name="email" placeholder="votre@email.com" required
                        value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required minlength="8">
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Afficher le mot de passe">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <span id="strengthText">Force du mot de passe</span>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                </div>
            </div>

            <!-- Termes -->
            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> et la
                    <a href="#" target="_blank">politique de confidentialité</a>
                </label>
            </div>

            <button type="submit" id="submitBtn"><i class="fas fa-user-plus"></i> Créer mon compte</button>
        </form>

        <div class="login-link">
            <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thème clair/sombre
            const themeToggle = document.getElementById('themeToggle');
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
            themeToggle.addEventListener('click', () => {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                if (isDark) {
                    document.documentElement.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                } else {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                }
            });

            // Toggle mot de passe
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                passwordInput.focus();
            });

            // Force du mot de passe
            passwordInput.addEventListener('input', function() {
                const pwd = this.value;
                const fill = document.getElementById('strengthFill');
                const text = document.getElementById('strengthText');
                let strength = 0;
                if (pwd.length >= 8) strength++;
                if (/[A-Z]/.test(pwd)) strength++;
                if (/[0-9]/.test(pwd)) strength++;
                if (/[^A-Za-z0-9]/.test(pwd)) strength++;
                fill.className = 'strength-fill';
                if (!pwd) {
                    fill.style.width = '0%';
                    text.textContent = 'Force du mot de passe';
                } else if (strength <= 1) {
                    fill.style.width = '33%';
                    fill.classList.add('strength-weak');
                    text.textContent = 'Faible';
                } else if (strength <= 2) {
                    fill.style.width = '66%';
                    fill.classList.add('strength-medium');
                    text.textContent = 'Moyen';
                } else {
                    fill.style.width = '100%';
                    fill.classList.add('strength-strong');
                    text.textContent = 'Fort';
                }
            });

            // Soumission formulaire
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');
            form.addEventListener('submit', function(e) {
                const terms = document.getElementById('terms');
                if (!terms.checked) {
                    e.preventDefault();
                    alert('Veuillez accepter les conditions d\'utilisation');
                    terms.focus();
                    return;
                }
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
                submitBtn.disabled = true;
            });

            // Animation champs
            const elements = document.querySelectorAll('.form-group, .terms, button');
            elements.forEach((el, i) => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    el.style.opacity = 1;
                    el.style.transform = 'translateY(0)';
                }, 100 + i * 100);
            });

            // Entrée = submit
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && !submitBtn.disabled) {
                    form.requestSubmit();
                }
            });
        });
    </script>

</body>

</html>