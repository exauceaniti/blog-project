<!DOCTYPE html>
<html lang="fr">
<head>
    <?php \Src\Core\Render\Fragment::meta(); ?>
   
</head>
<body>
    <?php \Src\Core\Render\Fragment::header(); ?>
    <?php \Src\Core\Render\Fragment::nav(); ?>

    <main>
        <?= $page_view ?>
    </main>

    <?php \Src\Core\Render\Fragment::footer(); ?>
</body>
</html>
