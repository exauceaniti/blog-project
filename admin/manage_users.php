<?php
// La classe ici gere la page d'administration des utilisateurs
// Elle gere tout ce qui cadre avec les utilisateurs voir : ajout
session_start();
require_once '../classes/connexion.php';
require_once '../classes/User.php';
//connexion a la base de donnees

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
// if (isset($_POST['supprimer'])) {
//     $id = $_POST['id'];
//     $user->supprimerUtilisateur($id);
//     header("Location: manage_users.php");
//     exit;
// }

//lire les utilisateurs
// $users = $user->voirUtilisateurs();
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
        <button type="submit" name="ajouter">Ajouter</button>
    </form>


    <h2>Liste des utilisateurs</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?php echo htmlspecialchars($user['email']); ?>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="supprimer">Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>