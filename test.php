<?php
require_once __DIR__ . '/config/connexion.php';
require_once __DIR__ . '/models/Post.php';
require_once __DIR__ . '/models/commentaire.php';
require_once __DIR__ . '/config/validator.php';
require_once __DIR__ . '/controllers/PostController.php';


session_start();
$_SESSION['user_id'] = 1; // simulate user login

// ✅ $connexion doit exister avant de passer au controller
$controller = new PostController($connexion);
$controller->create();
