<?php
require_once __DIR__ . '/../../config/connexion.php';
require_once __DIR__ . '/../../controllers/PostController.php';

$connexion = new Connexion();
$controller = new PostController($connexion);

// Récupérer tous les articles
$posts = $controller->getArticlesForPage(1, 50);
$totalArticles = $controller->getTotalArticles();
?>

<section class="admin-section">
    <h2>📝 Gestion des articles</h2>

    <p><strong>Nombre total d'articles :</strong> <?= $totalArticles ?></p>

    <?php if (empty($posts)): ?>
        <p style="color: red;">Aucun article trouvé.</p>
    <?php else: ?>
        <table id="table-posts">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Contenu</th> <!-- ✅ Ajouté -->
                    <th>Auteur</th>
                    <th>Date de publication</th>
                    <th>Média</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $article): ?>
                    <tr>
                        <td><?= htmlspecialchars($article['id']) ?></td>
                        <td><?= htmlspecialchars($article['titre']) ?></td>
                        <td><?= nl2br(htmlspecialchars($article['contenu'])) ?></td>
                        <td><?= htmlspecialchars($article['auteur_nom']) ?></td>
                        <td><?= htmlspecialchars($article['date_publication']) ?></td>
                        <td>
                            <?php if ($article['media_path']): ?>
                                <a href="<?= $article['media_path'] ?>" target="_blank">📎 Voir</a>
                            <?php else: ?>
                                Aucun
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?route=admin/edit_post&id=<?= $article['id'] ?>">✏️ Modifier</a>
                        </td>
                        <td>
                            <form action="index.php?route=admin/delete_post" method="post"
                                onsubmit="return confirm('Supprimer cet article ?')">
                                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                                <button type="submit">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</section>
<section id="add-post">
    <h3>➕ Ajouter un nouvel article</h3>
    <form action="index.php?route=admin/create_post" method="POST" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre de l'article" required>
        <textarea name="contenu" rows="5" placeholder="Contenu de l'article" required></textarea>
        <input type="file" name="media">
        <button type="submit">📤 Publier</button>
    </form>
</section>



<style>
    .admin-section {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    #table-posts {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    #table-posts th,
    #table-posts td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    #table-posts th {
        background: #2c3e50;
        color: white;
    }

    #table-posts tr:hover {
        background: #f0f0f0;
    }

    /* === STYLE DE LA SECTION D'AJOUT D'ARTICLE === */
    #add-post {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 25px;
        margin-top: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    #add-post:hover {
        transform: translateY(-2px);
    }

    /* Titre de la section */
    #add-post h3 {
        color: #2c3e50;
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: 600;
        text-align: center;
    }

    /* Formulaire d’ajout */
    #add-post form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    /* Champs texte et zone de texte */
    #add-post input[type="text"],
    #add-post textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
        font-family: "Segoe UI", sans-serif;
        transition: all 0.2s ease;
        resize: vertical;
    }

    #add-post input[type="text"]:focus,
    #add-post textarea:focus {
        border-color: #3498db;
        box-shadow: 0 0 6px rgba(52, 152, 219, 0.3);
        outline: none;
    }

    /* Champ de fichier */
    #add-post input[type="file"] {
        padding: 8px;
        border: 1px dashed #bbb;
        border-radius: 8px;
        background: #fafafa;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    #add-post input[type="file"]:hover {
        border-color: #3498db;
    }

    /* Bouton Publier */
    #add-post button {
        background: linear-gradient(135deg, #3498db, #2c80b4);
        color: white;
        border: none;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    #add-post button:hover {
        background: linear-gradient(135deg, #2c80b4, #1d6fa5);
        transform: scale(1.03);
    }

    #add-post button:active {
        transform: scale(0.98);
    }
</style>