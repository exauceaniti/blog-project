<?php
// header.php – en-tête réutilisable pour les pages publiques
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Mon Blog" ?></title>

    <!-- Lien vers le style global -->
    <link rel="stylesheet" href="/assets/css/header.css">

    <!-- Lien vers la police Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <a href="/"><i class="fa-solid fa-feather"></i> MonBlog</a>
            </div>

            <nav class="nav-links">
                <a href="/">Accueil</a>
                <a href="/articles">Articles</a>
                <a href="/contact">Contact</a>

                <!-- Bouton de connexion utilisateur -->
                <a href="/login" class="btn-login"><i class="fa-solid fa-user"></i> Connexion</a>

                <!-- Bouton pour accéder à l'administration -->
                <a href="/?route=admin/login" class="btn-admin"><i class="fa-solid fa-lock"></i> Admin</a>

            </nav>

        </div>
    </header>

    <main class="main-content">