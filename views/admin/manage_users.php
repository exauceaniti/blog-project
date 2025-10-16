<?php
// ===============================
// 🔹 Fichier : views/admin/manage_users.php
// ===============================

require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/UserController.php';

// Initialisation du contrôleur
$connexion = new Connexion();
$controller = new UserController($connexion);

// Récupération des utilisateurs
$utilisateurs = $controller->getAllUsers();

// Messages éventuels
$success = $_SESSION['success'] ?? null;
$errors = $_SESSION['errors'] ?? [];

// Nettoyage après affichage
unset($_SESSION['success'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-danger {
            background: #e74c3c;
            color: #fff;
            border: none;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-success {
            background: #2ecc71;
            color: #fff;
            border: none;
        }

        .btn-success:hover {
            background: #27ae60;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .message-success {
            background: #dff0d8;
            color: #3c763d;
        }

        .message-error {
            background: #f2dede;
            color: #a94442;
        }
    </style>
</head>

<body>

    <h1>👥 Gestion des utilisateurs</h1>

    <!-- Messages de succès ou d’erreur -->
    <?php if ($success): ?>
        <div class="message message-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <div class="message message-error"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>

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
            <?php foreach ($utilisateurs as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="action" value="change_role">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur
                                    </option>
                                </select>
                            </form>
                        <?php else: ?>
                            <?= htmlspecialchars($user['role']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                            <form method="post" style="display:inline"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <em>Vous-même</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>