<?php
require_once __DIR__ . '/../../controllers/AdminController.php';
session_start();

$adminController = new AdminController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($adminController->login($email, $password)) {
        // Redirection vers index.php avec route admin/dashboard
        header('Location: index.php?route=admin/dashboard');
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect ou vous n\'êtes pas admin.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Espace Administration</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php?route=admin/login">
            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <a href="index.php?route=home" class="back-link">← Retour au site</a>
    </div>
</body>

</html>