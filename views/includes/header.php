<?php
// Démarrer la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier le thème dans la session, sinon défaut clair
$currentTheme = $_SESSION['theme'] ?? 'light';
?>

<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo $currentTheme; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Mon Blog'; ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/header.css">
    <?php if (isset($additionalCSS)): ?>
        <link rel="stylesheet" href="<?php echo $additionalCSS; ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/uploads/favicon.ico">
</head>

<body>
    <header class="header">
        <nav class="nav container">
            <!-- Logo -->
            <div class="logo">
                <a href="/index.php" class="logo-link">
                    <span class="logo-text">MonBlog</span>
                </a>
            </div>

            <!-- Navigation principale -->
            <ul class="nav-menu" id="nav-menu">
                <li class="nav-item">
                    <a href="/index.php" class="nav-link">Accueil</a>
                </li>
                <li class="nav-item">
                    <a href="/index.php?action=articles" class="nav-link">Articles</a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Utilisateur connecté - VERSION SIMPLIFIÉE -->
                    <li class="nav-item">
                        <a href="/admin/dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/manage_posts.php" class="nav-link">Mes Articles</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link user-name">
                            👋 <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Invité"; ?>
                        </span>

                    </li>
                    <li class="nav-item">
                        <a href="/controllers/UserController.php?action=logout" class="nav-link">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <!-- Utilisateur non connecté -->
                    <li class="nav-item">
                        <a href="/views/login.php" class="nav-link">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a href="/views/register.php" class="nav-link register-btn">Inscription</a>
                    </li>
                <?php endif; ?>

                <!-- Bouton thème -->
                <li class="nav-item">
                    <button class="theme-toggle" id="theme-toggle" aria-label="Changer le thème">
                        <span class="theme-icon">
                            <?php echo $currentTheme === 'dark' ? '☀️' : '🌙'; ?>
                        </span>
                    </button>
                </li>
            </ul>

            <!-- Menu hamburger (mobile) -->
            <div class="hamburger" id="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>
    <script src="/assets/js/theme.js"></script>

    <main class="main-content">