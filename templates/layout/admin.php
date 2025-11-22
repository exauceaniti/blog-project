<!DOCTYPE html>
<html lang="fr">

<head>
    <?php \Src\Core\Render\Fragment::meta($page_title ?? 'Admin - Dashboard'); ?>

</head>

<body>
    <?php \Src\Core\Render\Fragment::header(); ?>

    <aside>
        <?php \Src\Core\Render\Fragment::sidebar($sidebar_params ?? []); ?>
    </aside>

    <main>
        <?= $page_view ?>
    </main>

    <?php \Src\Core\Render\Fragment::footer(); ?>
</body>

</html>