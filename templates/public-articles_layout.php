<?php

// Ici je vais injecter les éléments qui vont se retrouver dans le layout public.

$layout = new \controllers\layout\LayoutController();

$layout->autoTitle($_SERVER['REQUEST_URI']);
$layout->injectAll();
?>

