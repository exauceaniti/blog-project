<?php
session_start();
require_once 'views/includes/header.php';
require_once 'config/connexion.php';
require_once 'models/Post.php';
require_once 'models/commentaire.php';


// Connexion à la base de données
$connexion = new Connexion();
$postManager = new Post($connexion);
$commentaireManager = new commentaire($connexion);

// Vérification si l'id est bien un entier
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupération de l'article
$article = $postManager->getArticleById($articleId);

if (!$article) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// Récupération des commentaires pour cet article
$commentaires = $commentaireManager->voirCommentaires($articleId);

// Traitement de l'ajout de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter' && isset($_SESSION['user_id'])) {
    $contenu = trim($_POST['contenu']);
    $articleId = $_POST['articleId'] ?? null;

    if (!empty($contenu) && $articleId) {
        // Utiliser le bon ordre : contenu, articleId, auteurId
        $success = $commentaireManager->ajouterCommentaire($contenu, $articleId, $_SESSION['user_id']);

        if ($success) {
            header("Location: article.php?id=$articleId");
            exit;
        } else {
            echo '<p style="color:red;">Erreur lors de l\'ajout du commentaire.</p>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['titre']); ?></title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --text-color: #333;
            --text-light: #777;
            --border-color: #ddd;
            --success-color: #2ecc71;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f9f9f9;
        }

        main {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .post {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .post-title {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
            line-height: 1.3;
        }

        .post-meta {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .post-author {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 1rem;
        }

        .post-date {
            margin-left: auto;
        }

        .post-content {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .post-content p {
            margin-bottom: 1.5rem;
        }

        .comments-section {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .comments-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
            color: var(--secondary-color);
        }

        .comment {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1rem;
            background: #fafafa;
            border-radius: 6px;
        }

        .comment:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 1rem;
        }

        .comment-date {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-left: auto;
        }

        .comment-content {
            line-height: 1.6;
            color: var(--text-color);
        }

        .no-comments {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-style: italic;
        }

        .comment-form {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .form-title {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
            min-height: 120px;
            resize: vertical;
            transition: var(--transition);
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn:hover {
            background: var(--secondary-color);
        }

        .login-prompt {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-top: 2rem;
        }

        .login-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .success-message {
            background: var(--success-color);
            color: white;
            padding: 0.8rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .post-title {
                font-size: 1.8rem;
            }

            .post-content {
                font-size: 1rem;
            }

            .post,
            .comments-section {
                padding: 1.5rem;
            }
        }

        .add-comment-container {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        #add-comment-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
        }

        #add-comment-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .comment-form {
            background-color: #f9f9f9;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .login-required-message {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-required-message p {
            margin-bottom: 1rem;
            color: #856404;
            font-weight: 500;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-register {
            background-color: var(--secondary-color);
            color: white;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-login:hover,
        .btn-register:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* Animation pour l'apparition du formulaire */
        #comment-form-container {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-buttons {
                flex-direction: column;
            }

            .btn-login,
            .btn-register {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <main>
        <article class="post">
            <h1 class="post-title"><?php echo htmlspecialchars($article['titre']); ?></h1>
            <div class="post-meta">
                <span class="post-author"><?= htmlspecialchars($article['auteur']); ?></span>
                <span class="post-date"><?= date('d/m/Y à H:i', strtotime($article['date_publication'])); ?></span>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
            </div>
        </article>


        <section class="comments-section">
            <h2 class="comments-title">Commentaires</h2>

            <?php if (empty($commentaires)): ?>
                <div class="no-comments">
                    <p>Aucun commentaire pour cet article !</p>
                </div>
            <?php else: ?>
                <?php foreach ($commentaires as $comment): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <span class="comment-author"><?= htmlspecialchars($comment['auteur']); ?></span>
                            <span class="comment-date"><?= date('d/m/Y à H:i', strtotime($comment['date_Commentaire'])); ?></span>
                        </div>
                        <div class="comment-content">
                            <?= nl2br(htmlspecialchars($comment['contenu'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="add-comment-container">
                <button id="add-comment-btn" class="btn btn-primary">Ajouter un commentaire</button>

                <div id="comment-form-container" style="display: none;">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="comment-form">
                            <h3 class="form-title">Votre commentaire</h3>
                            <form action="" method="POST">
                                <input type="hidden" name="action" value="ajouter">
                                <input type="hidden" name="articleId" value="<?= $articleId ?>">
                                <div class="form-group">
                                    <textarea name="contenu" required placeholder="Partagez votre pensée..."></textarea>
                                </div>
                                <button type="submit" class="btn">Publier le commentaire</button>
                            </form>

                        </div>
                    <?php else: ?>
                        <div class="login-required-message">
                            <p>Vous devez être connecté pour ajouter un commentaire.</p>
                            <div class="auth-buttons">
                                <a href="login.php" class="btn btn-login">Se connecter</a>
                                <a href="register.php" class="btn btn-register">Créer un compte</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            </div>
        </section>


    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addCommentBtn = document.getElementById('add-comment-btn');
            const commentFormContainer = document.getElementById('comment-form-container');

            addCommentBtn.addEventListener('click', function() {
                // Basculer l'affichage du formulaire
                if (commentFormContainer.style.display === 'none') {
                    commentFormContainer.style.display = 'block';
                    addCommentBtn.textContent = 'Masquer le formulaire';
                } else {
                    commentFormContainer.style.display = 'none';
                    addCommentBtn.textContent = 'Ajouter un commentaire';
                }
            });

            // Si l'utilisateur est connecté et qu'il y a une erreur de formulaire, afficher automatiquement le formulaire
            <?php if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                commentFormContainer.style.display = 'block';
                addCommentBtn.textContent = 'Masquer le formulaire';
            <?php endif; ?>
        });
    </script>

</body>

</html>