<?php

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5;

// Récupérer les articles pour la page
$articles = [];

// Nombre total de pages pour la pagination
$totalArticles = 100;
$totalPages = (int) ceil($totalArticles / $limit);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Blog - Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/header.css">

    <!-- Lien vers la police Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #7c3aed;
            --primary-hover: #6d28d9;
            --background-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 14px rgba(124, 58, 237, 0.1);

            /* COULEURS GRAPHICART STYLE */
            --accent-1: #ec4899;
            --accent-2: #8b5cf6;
            --accent-3: #10b981;
            --gradient-primary: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);

            /* Espacements */
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --border-radius: 12px;
        }

        [data-theme="dark"] {
            --primary-color: #8b5cf6;
            --primary-hover: #a78bfa;
            --background-color: #0f0f23;
            --surface-color: #1a1a2e;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #2d3748;
            --shadow: 0 4px 20px rgba(139, 92, 246, 0.15);

            /* GRADIENTS SOMBRE ÉLÉGANT */
            --gradient-primary: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: var(--background-color);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .article-card {
            background: var(--surface-color);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .article-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .article-card h2 {
            margin-top: 0;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            background: var(--primary-color);
            color: var(--accent-1);
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background: var(--primary-hover);
        }

        .pagination a:hover {
            background: var(--primary-hover);
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="container">
            <div class="logo">
                <a href="/"><i class="fa-solid fa-feather"></i> MonBlog</a>
            </div>

            <nav class="nav-links">
                <a href="/">Accueil</a>
                <a href="/articles">Articles</a>
                <a href="/contact">Contact</a>

                <!-- Bouton de connexion utilisateur -->
                <a href="/login" class="btn-login"><i class="fa-solid fa-user"></i> Connexion</a>

                <!-- Bouton pour accéder à l'administration -->
                <a href="/?route=admin/login" class="btn-admin"><i class="fa-solid fa-lock"></i> Admin</a>

            </nav>

        </div>
    </header>

    <main>
        <section>
            <?php echo $page_view; ?>
        </section>
        <aside></aside>
    </main>



</body>

</html>