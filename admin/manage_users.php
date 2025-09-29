<?php
// La classe ici gere la page d'administration des utilisateurs
// Elle gere tout ce qui cadre avec les utilisateurs voir : ajout
session_start();
require_once '../config/connexion.php';
require_once '../models/User.php';
//connexion a la base de donnees

// Verfication du role qu'il sagit bien de l'administrateur.
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}
// ...existing code...

$connexion = new Connexion();
$user = new User($connexion);
$conn = $connexion->connecter(); // on recupere l'objet PDO


// Ajouter un utilisateur
if (isset($_POST['ajouter'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user->sInscrire($email, $password);
    header("Location: manage_users.php");
    exit;
}

// Supprimer un utilisateur
if (isset($_POST['supprimer'])) {
    $id = $_POST['id'];
    $user->supprimerUtilisateur($id);
    header("Location: manage_users.php");
    exit;
}

// Modifier le rôle d'un utilisateur
if (isset($_POST['changer_role'])) {
    $id = $_POST['id'];
    $nouveauRole = $_POST['nouveau_role'];
    $user->changerRole($id, $nouveauRole);
    header("Location: manage_users.php");
    exit;
}

// lire les utilisateurs
$users = $user->voirUtilisateurs();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Gestion des utilisateurs</h1>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="ajouter" onclick="return confirm('Ajouter un nouveau utilisateur ?');">Ajouter</button>
    </form>


    <h2>Liste des utilisateurs</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit" name="supprimer" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</button>
                        </form>
                        <?php if ($user['role'] === 'admin'): ?>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="nouveau_role" value="user">
                                <button type="submit" name="changer_role" onclick="return confirm('Rétrograder cet admin en utilisateur ?');">Passer en user</button>
                            </form>
                        <?php else: ?>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="nouveau_role" value="admin">
                                <button type="submit" name="changer_role" onclick="return confirm('Promouvoir cet utilisateur en admin ?');">Passer en admin</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
</body>

</html>