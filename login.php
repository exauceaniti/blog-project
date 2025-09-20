<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion a mon blog</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main class="login-container">
        <h1>Connexion</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?= $_SESSION['error_message'] ?></div>
        <?php unset($_SESSION['error_message']);
        endif; ?>


        /*Formulaire de connexion avec les placeholder pour tout les champs*/
        <form action="handlers/user_handlers.php" method="POST">
            <input type="hidden" name="action" value="connexion">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Votre email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
    </main>


    <?php include 'includes/footer.php'; ?>
</body>

</html>