<?php
// views/admin/dashboard.php

// Vérification admin déjà faite dans routes/admin.php, pas besoin de session_start ici
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>
    <h1>Bienvenue sur le dashboard Admin</h1>
    <p>Utilisateur connecté : <?= htmlspecialchars($_SESSION['user']['email'] ?? 'Inconnu') ?></p>

    <ul>
        <li><a href="index.php?route=admin/manage_posts">Gérer les articles</a></li>
        <li><a href="index.php?route=admin/manage_comments">Gérer les commentaires</a></li>
        <li><a href="index.php?route=admin/manage_users">Gérer les utilisateurs</a></li>
        <li><a href="index.php?route=admin/logout">Se déconnecter</a></li>
    </ul>
</body>

</html>