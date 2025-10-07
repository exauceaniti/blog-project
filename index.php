<?php
session_start();

// On récupère la route demandée
$route = $_GET['route'] ?? 'home';

// Si c'est une route admin
if (str_starts_with($route, 'admin/')) {
    require __DIR__ . '/routes/admin.php';
} else {
    require __DIR__ . '/routes/public.php';
}
