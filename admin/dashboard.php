<?php
session_start();
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../controllers/PostController.php';

$connexion = new Connexion();
$controller = new PostController($connexion);

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

// Gestion des actions CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $controller->create();
    } elseif ($action === 'update' && $id) {
        $controller->update($id);
    }
}

// Actions GET
if ($action === 'delete' && $id) {
    $controller->delete($id);
}

// Récupérer les articles pour affichage
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$articles = $controller->getArticlesForPage($page, 50);
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f4f4f4;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        a.button {
            padding: 5px 10px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }

        a.button:hover {
            background: #0056b3;
        }

        .delete {
            background: #dc3545;
        }

        .delete:hover {
            background: #a71d2a;
        }
    </style>
</head>

<body>

    <h1>Admin Dashboard</h1>

    <p>
        <a href="?action=create" class="button">Ajouter un article</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Média</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= $article['id'] ?></td>
                    <td><?= htmlspecialchars($article['titre']) ?></td>
                    <td><?= htmlspecialchars($article['auteur_nom'] ?? 'Inconnu') ?></td>
                    <td><?= $article['date_publication'] ?></td>
                    <td>
                        <?php if (!empty($article['media_path'])): ?>
                            <?php if (str_contains($article['media_type'], 'image')): ?>
                                <img src="../<?= $article['media_path'] ?>" style="max-width:100px;">
                            <?php else: ?>
                                <a href="../<?= $article['media_path'] ?>" target="_blank">Voir</a>
                            <?php endif; ?>
                        <?php else: ?>
                            Aucun
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?action=update&id=<?= $article['id'] ?>" class="button">Éditer</a>
                        <a href="?action=delete&id=<?= $article['id'] ?>" class="button delete"
                            onclick="return confirm('Supprimer cet article ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>



</body>

</html>