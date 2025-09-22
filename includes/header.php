<?php
// includes/header.php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Project</title>
    <style>
        /* Reset rapide */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
        }

        /* HEADER */
        header {
            background: #0a192f;
            color: white;
            padding: 15px 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo a {
            text-decoration: none;
            color: #64ffda;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links li {
            display: inline-block;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background: #05b1cff1;
            color: #0a192f;
        }

        /* MAIN */
        main {
            padding: 20px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="/index.php">Mon Blog</a>
            </div>
            <ul class="nav-links">
                <li><a href="/index.php">Accueil</a></li>
                <li><a href="/admin/dashboard.php">Admin</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/handlers/user_handlers.php?action=logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/login.php">Connexion</a></li>
                    <li><a href="/register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>