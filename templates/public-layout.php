<?php

//Ici je vais injecter les element qui vont se retrouver dans le layout public.

$layout = new \controllers\layout\LayoutController();

$layout->autoTitle($_SERVER['REQUEST_URI']);
$layout->injectAll();

?>

<main>
    <?= $page_view ?>
</main>

