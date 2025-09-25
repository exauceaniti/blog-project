<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si déjà connecté, on redirige vers dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

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
    <title>Inscription - GraphicArt Blog</title>
    <link rel="stylesheet" href="/assets/css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Background animé -->
    <div class="register-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>

    <!-- Bouton thème -->
    <button class="theme-toggle-register" onclick="toggleTheme()">
        <i class="fas fa-moon"></i>
    </button>

    <div class="register-container">
        <!-- En-tête -->
        <div class="register-header">
            <h1 class="register-title">
                <i class="fas fa-user-plus"></i>
                Créer un compte
            </h1>
            <p class="register-subtitle">Rejoignez notre communauté et commencez à partager</p>
        </div>

        <!-- Barre de progression -->
        <div class="register-progress">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-step">
                <div class="step-number active" id="step1">1</div>
                <div class="step-label">Informations</div>
            </div>
            <div class="progress-step">
                <div class="step-number" id="step2">2</div>
                <div class="step-label">Sécurité</div>
            </div>
            <div class="progress-step">
                <div class="step-number" id="step3">3</div>
                <div class="step-label">Finalisation</div>
            </div>
        </div>

        <!-- Messages d'erreur/succès -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?= htmlspecialchars($_SESSION['error_message'] ?? 'Une erreur est survenue lors de l\'inscription') ?></span>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($_SESSION['success_message'] ?? 'Inscription réussie !') ?></span>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form action="../controllers/UserController.php" method="POST" class="register-form" id="registerForm">
            <input type="hidden" name="action" value="inscription">

            <!-- Étape 1: Informations personnelles -->
            <div class="form-step active" id="step1-content">
                <div class="form-group">
                    <label for="nom">
                        <i class="fas fa-user"></i> Nom complet
                    </label>
                    <input type="text"
                        id="nom"
                        name="nom"
                        class="form-input"
                        required
                        placeholder="Votre nom complet"
                        value="<?= htmlspecialchars($_SESSION['form_data']['nom'] ?? '') ?>"
                        autocomplete="name">
                    <i class="fas fa-check validation-icon valid"></i>
                    <i class="fas fa-times validation-icon invalid"></i>
                </div>

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
                    <i class="fas fa-check validation-icon valid"></i>
                    <i class="fas fa-times validation-icon invalid"></i>
                </div>

                <button type="button" class="register-button btn-primary" onclick="nextStep(2)">
                    <i class="fas fa-arrow-right"></i>
                    <span>Suivant</span>
                </button>
            </div>

            <!-- Étape 2: Sécurité -->
            <div class="form-step" id="step2-content">
                <div class="form-group password-toggle">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mot de passe
                    </label>
                    <input type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        required
                        placeholder="Créez un mot de passe sécurisé"
                        autocomplete="new-password"
                        minlength="6">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <i class="fas fa-check validation-icon valid"></i>
                    <i class="fas fa-times validation-icon invalid"></i>

                    <!-- Indicateur de force du mot de passe -->
                    <div class="password-strength">
                        <div class="strength-meter">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Force du mot de passe</div>
                    </div>
                </div>

                <div class="form-group password-toggle">
                    <label for="confirmPassword">
                        <i class="fas fa-lock"></i> Confirmer le mot de passe
                    </label>
                    <input type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        class="form-input"
                        required
                        placeholder="Confirmez votre mot de passe"
                        autocomplete="new-password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmPassword')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <i class="fas fa-check validation-icon valid"></i>
                    <i class="fas fa-times validation-icon invalid"></i>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="button" class="register-button btn-secondary" onclick="prevStep(1)" style="flex: 1;">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </button>
                    <button type="button" class="register-button btn-primary" onclick="nextStep(3)" style="flex: 1;">
                        <i class="fas fa-arrow-right"></i>
                        <span>Suivant</span>
                    </button>
                </div>
            </div>

            <!-- Étape 3: Finalisation -->
            <div class="form-step" id="step3-content">
                <div class="terms-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="terms-text">
                        J'accepte les <a href="/terms.php" target="_blank">conditions d'utilisation</a>
                        et la <a href="/privacy.php" target="_blank">politique de confidentialité</a>.
                        Je comprends que mes données seront traitées conformément à la réglementation en vigueur.
                    </label>
                </div>

                <div class="terms-group">
                    <input type="checkbox" id="newsletter" name="newsletter">
                    <label for="newsletter" class="terms-text">
                        Je souhaite recevoir les newsletters et les dernières actualités (optionnel).
                    </label>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="button" class="register-button btn-secondary" onclick="prevStep(2)" style="flex: 1;">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </button>
                    <button type="submit" class="register-button btn-success" id="submitBtn" style="flex: 1;">
                        <i class="fas fa-check"></i>
                        <span>Créer mon compte</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Lien de connexion -->
        <div class="register-links">
            <p>Déjà un compte ?
                <a href="login.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>"
                    class="register-link">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </a>
            </p>
        </div>
    </div>

    <?php unset($_SESSION['form_data']); ?>

    <script>
        // Gestion du thème
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            const icon = document.querySelector('.theme-toggle-register i');
            icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        // Appliquer le thème sauvegardé
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);

            const icon = document.querySelector('.theme-toggle-register i');
            icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

            updateProgress(1);
        });

        // Navigation par étapes
        let currentStep = 1;

        function nextStep(step) {
            if (validateStep(currentStep)) {
                showStep(step);
                currentStep = step;
                updateProgress(step);
            }
        }

        function prevStep(step) {
            showStep(step);
            currentStep = step;
            updateProgress(step);
        }

        function showStep(step) {
            // Cacher toutes les étapes
            document.querySelectorAll('.form-step').forEach(el => {
                el.classList.remove('active');
            });

            // Afficher l'étape courante
            document.getElementById(`step${step}-content`).classList.add('active');
        }

        function updateProgress(step) {
            // Mettre à jour les numéros d'étape
            for (let i = 1; i <= 3; i++) {
                const stepEl = document.getElementById(`step${i}`);
                stepEl.classList.remove('active', 'completed');

                if (i < step) {
                    stepEl.classList.add('completed');
                } else if (i === step) {
                    stepEl.classList.add('active');
                }
            }

            // Mettre à jour la barre de progression
            const progress = ((step - 1) / 2) * 100;
            document.getElementById('progressFill').style.width = `${progress}%`;
        }

        // Validation des étapes
        function validateStep(step) {
            switch (step) {
                case 1:
                    const nom = document.getElementById('nom');
                    const email = document.getElementById('email');

                    if (!nom.value.trim()) {
                        showError(nom, 'Le nom est requis');
                        return false;
                    }

                    if (!validateEmail(email.value)) {
                        showError(email, 'Email invalide');
                        return false;
                    }

                    return true;

                case 2:
                    const password = document.getElementById('password');
                    const confirmPassword = document.getElementById('confirmPassword');

                    if (password.value.length < 6) {
                        showError(password, 'Minimum 6 caractères');
                        return false;
                    }

                    if (password.value !== confirmPassword.value) {
                        showError(confirmPassword, 'Les mots de passe ne correspondent pas');
                        return false;
                    }

                    return true;

                default:
                    return true;
            }
        }

        function showError(input, message) {
            input.classList.add('invalid');
            input.classList.remove('valid');

            // Notification toast simple
            alert(message);
            input.focus();
        }

        // Validation email
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Toggle visibilité mot de passe
        function togglePasswordVisibility(fieldId) {
            const input = document.getElementById(fieldId);
            const toggleIcon = input.parentElement.querySelector('.toggle-password i');

            if (input.type === 'password') {
                input.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }

        // Force du mot de passe
        document.getElementById('password').addEventListener('input', function() {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const password = this.value;

            let strength = 0;
            let text = '';
            let className = '';

            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    text = 'Faible';
                    className = 'weak';
                    break;
                case 2:
                case 3:
                    text = 'Moyen';
                    className = 'medium';
                    break;
                case 4:
                    text = 'Fort';
                    className = 'strong';
                    break;
            }

            strengthFill.className = `strength-fill ${className}`;
            strengthText.textContent = `Force du mot de passe: ${text}`;
            strengthText.style.color = strengthFill.style.background;
        });

        // Validation en temps réel
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('valid', 'invalid');

                if (this.value.trim() === '') return;

                if (this.type === 'email') {
                    this.classList.add(validateEmail(this.value) ? 'valid' : 'invalid');
                } else if (this.id === 'confirmPassword') {
                    const password = document.getElementById('password').value;
                    this.classList.add(this.value === password ? 'valid' : 'invalid');
                } else {
                    this.classList.add('valid');
                }
            });
        });

        // Soumission du formulaire
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalContent = submitBtn.innerHTML;

            if (!validateStep(3)) {
                e.preventDefault();
                return;
            }

            if (!document.getElementById('terms').checked) {
                e.preventDefault();
                alert('Veuillez accepter les conditions d\'utilisation');
                return;
            }

            // Animation de chargement
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';
            submitBtn.classList.add('loading');
        });

        // Raccourci clavier
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                if (currentStep < 3) {
                    nextStep(currentStep + 1);
                }
            }
        });
    </script>

    <style>
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: slideIn 0.3s ease;
        }

        .btn-secondary {
            background: var(--text-secondary);
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }
    </style>
</body>

</html>