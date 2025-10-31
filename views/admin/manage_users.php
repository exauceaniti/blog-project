<?php
// Chargement du contrôleur et récupération des utilisateurs
require_once __DIR__ . '/../../controllers/UserController.php';

$connexion = new Connexion();
$controller = new UserController($connexion);

// Appel de la méthode et extraction des données

$users = $controller->manageUsers();
$user = $users ?? [];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 10px;
        }

        .user-count {
            text-align: center;
            font-size: 18px;
            margin-bottom: 30px;
            color: #7f8c8d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .actions form {
            display: inline-block;
            margin-right: 5px;
        }

        .actions button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .actions button:hover {
            background-color: #2980b9;
        }

        select {
            padding: 5px;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <h1>👤 Gestion des utilisateurs</h1>

    <div class="user-count">
        <?php
        echo "Nombre total d'utilisateurs : <strong>" . count($users) . "</strong>";
        ?>
    </div>

    <?php if (empty($users)): ?>
        <p style="text-align:center; color:#e74c3c;">Aucun utilisateur trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td class="actions">
                            <!-- Changer rôle -->
                            <form action="index.php?route=admin/change_role" method="post">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <select name="newRole">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit">Changer</button>
                            </form>

                            <!-- Supprimer -->
                            <form action="index.php?route=admin/delete_user" method="post"
                                onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>

</html>