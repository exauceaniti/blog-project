<?php
session_start();
require_once "../config/connexion.php";

// Sécurité : accès admin uniquement
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

$connexion = new Connexion();

// Suppression d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $connexion->executerRequete("DELETE FROM commentaires WHERE id = ?", [$id]);
    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Commentaire supprimé.'];
    header("Location: manage_comments.php");
    exit;
}

// Récupération des commentaires
$comments = $connexion->executerRequete("
    SELECT c.id, c.contenu, c.date_commentaire, u.nom AS auteur, a.titre AS article
    FROM commentaires c
    JOIN utilisateurs u ON c.auteur_id = u.id
    JOIN articles a ON c.article_id = a.id
    ORDER BY c.date_commentaire DESC
")->fetchAll();

// ...existing code...
$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des commentaires</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <!-- ... Sidebar identique à dashboard ... -->
        </aside>
        <main class="admin-main">
            <header class="admin-topbar">
                <h1>Commentaires</h1>
            </header>

            <?php if ($toast): ?>
                <div class="toast <?= $toast['type'] ?>">
                    <?= htmlspecialchars($toast['message']) ?>
                </div>
            <?php endif; ?>

            <section class="card">
                <div class="card-header">
                    <h2>Liste des commentaires (<?= count($comments) ?>)</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($comments)): ?>
                        <div class="empty">Aucun commentaire pour le moment.</div>
                    <?php else: ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Auteur</th>
                                    <th>Article</th>
                                    <th>Contenu</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comments as $comment): ?>
                                    <tr>
                                        <td><?= $comment['id'] ?></td>
                                        <td><?= htmlspecialchars($comment['auteur']) ?></td>
                                        <td><?= htmlspecialchars($comment['article']) ?></td>
                                        <td><?= htmlspecialchars(mb_strimwidth($comment['contenu'], 0, 60, '...')) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($comment['date_commentaire'])) ?></td>

                                        <form method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                            <input type="hidden" name="delete_id" value="<?= $comment['id'] ?>">
                                            <button class="btn-danger" type="submit">Supprimer</button>
                                        </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>