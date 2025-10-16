<?php

// ===============================
// 🔹 Inclusion des fichiers de base
// ===============================
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/config/validator.php';

// ===============================
// 🔹 Initialisation
// ===============================
$connexion = new Connexion();
$userModel = new User($connexion);

$isLoggedIn = isset($_SESSION['user']);
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : null;

// ===============================
// 🔹 Détection de la route demandée
// ===============================
$route = $_GET['route'] ?? 'public/home'; // route par défaut

// Nettoyage de la route
$route = trim($route, '/');

// ===============================
// 🔹 Gestion des accès et redirections globales
// ===============================

// Si utilisateur NON connecté et tente d’accéder à une zone protégée admin
if (str_starts_with($route, 'admin/') && !$isLoggedIn) {
    $_SESSION['errors'] = ["Veuillez vous connecter pour accéder à l'administration."];
    header('Location: index.php?route=public/login');
    exit;
}

// Si utilisateur connecté mais n’est pas admin et tente une route admin
if (str_starts_with($route, 'admin/') && $userRole !== 'admin') {
    $_SESSION['errors'] = ["Accès refusé : vous n’êtes pas administrateur."];
    header('Location: index.php?route=public/home');
    exit;
}

// Si utilisateur connecté et essaie d’aller sur login/register
if ($isLoggedIn && in_array($route, ['public/login', 'public/register'])) {
    $redirect = ($userRole === 'admin')
        ? 'index.php?route=admin/dashboard'
        : 'index.php?route=public/home';
    header('Location: ' . $redirect);
    exit;
}

// ===============================
// 🔹 Inclusion dynamique selon le type de route
// ===============================
if (str_starts_with($route, 'admin/')) {
    require_once __DIR__ . '/routes/admin.php';
} else {
    require_once __DIR__ . '/routes/public.php';
}
