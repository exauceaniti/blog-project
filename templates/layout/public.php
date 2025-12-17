<!DOCTYPE html>
<html lang="fr">

<head>
    <?php \App\Core\Render\Fragment::meta($page_title ?? 'Mon blog-Exau'); ?>

</head>

<body class="public-layout">
    <?php \App\Core\Render\Fragment::header(); ?>

    <div class="content-wrapper">
        <main class="public-main">
            <?= $page_view ?>
        </main>
    </div>

    <?php \App\Core\Render\Fragment::footer(); ?>
</body>

</html>