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
<<<<<<< HEAD
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
=======
    <title>Inscription | GraphiCart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            /* THÈME CLAIR - Inspiré GraphiCart mais plus vibrant */
            --primary-color: #7c3aed;
            --primary-hover: #6d28d9;
            --background-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 14px rgba(124, 58, 237, 0.1);
            --accent-1: #ec4899;
            --accent-2: #8b5cf6;
            --accent-3: #10b981;
            --gradient-primary: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --border-radius: 12px;
        }

        [data-theme="dark"] {
            --primary-color: #8b5cf6;
            --primary-hover: #a78bfa;
            --background-color: #0f0f23;
            --surface-color: #1a1a2e;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #2d3748;
            --shadow: 0 4px 20px rgba(139, 92, 246, 0.15);
            --gradient-primary: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s, color 0.3s;
            padding: var(--spacing-sm);
            line-height: 1.6;
        }

        .register-container {
            background: var(--surface-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-md);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .register-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.15);
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        h2 {
            text-align: center;
            margin-bottom: var(--spacing-md);
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: var(--spacing-sm);
            border-left: 4px solid #dc2626;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: var(--spacing-sm);
            border-left: 4px solid #10b981;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: var(--spacing-sm);
            position: relative;
        }

        label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .input-container,
        .password-container {
            position: relative;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: var(--surface-color);
            color: var(--text-primary);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: var(--surface-color);
            border: 2px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1rem;
            padding: 0.4rem;
            width: 2.2rem;
            height: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .toggle-password:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-50%) scale(1.05);
        }

        .toggle-password:active {
            transform: translateY(-50%) scale(0.95);
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }

        .strength-weak {
            width: 33%;
            background: #ef4444;
        }

        .strength-medium {
            width: 66%;
            background: #f59e0b;
        }

        .strength-strong {
            width: 100%;
            background: #10b981;
        }

        .terms {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin: 1.5rem 0 1rem;
            font-size: 0.9rem;
        }

        .terms input[type="checkbox"] {
            accent-color: var(--primary-color);
            margin-top: 0.2rem;
            cursor: pointer;
        }

        .terms label {
            font-weight: normal;
            cursor: pointer;
        }

        .terms a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        button[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: var(--spacing-sm);
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .theme-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text-secondary);
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.3s, transform 0.3s;
        }

        .theme-toggle:hover {
            background-color: rgba(124, 58, 237, 0.1);
            transform: rotate(30deg);
        }

        @media (max-width: 480px) {
            .register-container {
                padding: var(--spacing-sm);
            }

            h2 {
                font-size: 1.8rem;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <button class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>

        <h2><i class="fas fa-user-plus"></i> Inscription</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error_message'] ?? 'Erreur lors de l\'inscription' ?>
>>>>>>> retour-article-media
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
<<<<<<< HEAD
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
=======
            <div class="success">
                <i class="fas fa-check-circle"></i>
                Inscription réussie ! Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>

        <form action="controller/UserController.php" method="POST" id="registerForm">
            <input type="hidden" name="action" value="inscription">

            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom complet:</label>
                <div class="input-container">
                    <input type="text" id="nom" name="nom" placeholder="Votre nom complet" required
                        value="<?= htmlspecialchars($_SESSION['form_data']['nom'] ?? '') ?>">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                <div class="input-container">
                    <input type="email" id="email" name="email" placeholder="votre@email.com" required
                        value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" id="togglePassword">
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

            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    J'accepte les <a href="#">conditions d'utilisation</a> et la
                    <a href="#">politique de confidentialité</a>
                </label>
            </div>

            <button type="submit">
                <i class="fas fa-user-plus"></i> Créer mon compte
            </button>
>>>>>>> retour-article-media
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

<<<<<<< HEAD
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
=======
    <script>
        // Toggle du thème sombre
        const themeToggle = document.getElementById('themeToggle');
        const currentTheme = localStorage.getItem('theme') || 'light';

        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');

            if (currentTheme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        });

        // Toggle pour afficher/masquer le mot de passe
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                this.style.transform = 'translateY(-50%) scale(0.9)';

                setTimeout(() => {
                    this.innerHTML = type === 'password' ?
                        '<i class="fas fa-eye"></i>' :
                        '<i class="fas fa-eye-slash"></i>';

                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 150);

                passwordInput.focus();
            });

            passwordInput.addEventListener('focus', function() {
                togglePassword.style.borderColor = 'var(--primary-color)';
            });

            passwordInput.addEventListener('blur', function() {
                togglePassword.style.borderColor = 'var(--border-color)';
            });
        }

        // Indicateur de force du mot de passe
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let text = 'Faible';

            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            strengthFill.className = 'strength-fill';

            if (password.length === 0) {
                strengthFill.style.width = '0%';
                strengthText.textContent = 'Force du mot de passe';
            } else if (strength <= 1) {
                strengthFill.style.width = '33%';
                strengthFill.classList.add('strength-weak');
                strengthText.textContent = 'Faible';
            } else if (strength <= 2) {
                strengthFill.style.width = '66%';
                strengthFill.classList.add('strength-medium');
                strengthText.textContent = 'Moyen';
            } else {
                strengthFill.style.width = '100%';
                strengthFill.classList.add('strength-strong');
                strengthText.textContent = 'Fort';
            }
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.form-group, .terms, button');
            formElements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
        });

        // Validation du formulaire
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                alert('Veuillez accepter les conditions d\'utilisation');
                terms.focus();
>>>>>>> retour-article-media
            }
        });
    </script>

<<<<<<< HEAD
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
=======
    <?php unset($_SESSION['form_data']); ?>
>>>>>>> retour-article-media
</body>

</html>