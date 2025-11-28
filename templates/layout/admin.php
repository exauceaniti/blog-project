<!DOCTYPE html>
<html lang="fr">

<head>
    <?php \Src\Core\Render\Fragment::meta($page_title ?? 'Admin - Dashboard'); ?>

</head>

<body>
    <aside>
        <?php \Src\Core\Render\Fragment::sidebar($sidebar_params ?? []); ?>
    </aside>

    <main>
        <?= $page_view ?>
    </main>
</body>

</html>