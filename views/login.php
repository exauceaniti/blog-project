<?php
session_start();

// Stocker l'URL de redirection si fournie
if (isset($_GET['redirect'])) {
    $_SESSION['redirect_url'] = $_GET['redirect'];
}

// Si déjà connecté, on redirige vers dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
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
    <style>
        :root {
            --primary-color: #7c3aed;
            --primary-hover: #6d28d9;
            --background-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --error-color: #ef4444;
            --gradient-primary: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        [data-theme="dark"] {
            --primary-color: #8b5cf6;
            --primary-hover: #a78bfa;
            --background-color: #0f0f23;
            --surface-color: #1a1a2e;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #2d3748;
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
            transition: var(--transition);
            padding: 1rem;
        }

        .login-container {
            background: var(--surface-color);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            position: relative;
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
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background-color: rgba(124, 58, 237, 0.1);
            transform: rotate(30deg);
        }

        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--error-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .success-message {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--success-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .input-container {
            position: relative;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 3rem 0.75rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: var(--surface-color);
            color: var(--text-primary);
        }

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
            z-index: 2;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1rem;
            padding: 0.5rem;
            transition: var(--transition);
            z-index: 2;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .toggle-password:hover {
            background-color: rgba(124, 58, 237, 0.1);
            color: var(--primary-color);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            accent-color: var(--primary-color);
            width: 16px;
            height: 16px;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .forgot-password:hover {
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
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        button[type="submit"]:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        button[type="submit"]:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .fa-spinner {
            animation: spin 1s linear infinite;
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

        .login-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <button class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>

        <h1><i class="fas fa-sign-in-alt"></i> Connexion</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error_message'] ?? 'Erreur de connexion') ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success_message'] ?? 'Connexion réussie') ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <form action="../controllers/UserController.php" method="POST" id="loginForm">
            <input type="hidden" name="action" value="connexion">

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <div class="input-container">
                    <input type="email" id="email" name="email" required
                        value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"
                        placeholder="votre@email.com">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
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

            <button type="submit" id="submitBtn">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <p class="register-link">
            Pas encore de compte ?
            <a href="register.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>">
                Inscrivez-vous ici
            </a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du thème
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                const currentTheme = localStorage.getItem('theme') || 'light';

                if (currentTheme === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                }

                themeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

                    if (isDark) {
                        document.documentElement.removeAttribute('data-theme');
                        localStorage.setItem('theme', 'light');
                        this.innerHTML = '<i class="fas fa-moon"></i>';
                    } else {
                        document.documentElement.setAttribute('data-theme', 'dark');
                        localStorage.setItem('theme', 'dark');
                        this.innerHTML = '<i class="fas fa-sun"></i>';
                    }
                });
            }

            // Toggle visibilité mot de passe - CORRIGÉ
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';

                    if (isPassword) {
                        passwordInput.type = 'text';
                        this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    } else {
                        passwordInput.type = 'password';
                        this.innerHTML = '<i class="fas fa-eye"></i>';
                    }

                    // Remettre le focus sur le champ
                    passwordInput.focus();
                });
            }

            // Gestion de la soumission du formulaire
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');

            if (loginForm && submitBtn) {
                loginForm.addEventListener('submit', function(e) {
                    // Animation de chargement
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
                    submitBtn.disabled = true;
                });
            }

            // Validation en temps réel
            const emailInput = document.getElementById('email');

            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    if (this.validity.valid) {
                        this.style.borderColor = '#10b981';
                    } else {
                        this.style.borderColor = '#ef4444';
                    }
                });
            }

            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    if (this.value.length >= 6) {
                        this.style.borderColor = '#10b981';
                    } else {
                        this.style.borderColor = '#ef4444';
                    }
                });
            }
        });
    </script>

    <?php unset($_SESSION['form_data']); ?>
</body>

</html>