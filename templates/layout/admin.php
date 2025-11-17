<?php

// Initialisation du contrôleur de layout
// Ce contrôleur gère les données globales (titre, thème, rôle, session)
// et orchestre l’injection des fragments HTML dans le layout public.
$layout = new \controllers\layout\LayoutController();

// Définition automatique du titre selon la route actuelle
// Utilise PageTitleResolver pour éviter toute logique répétée.
$layout->autoTitle($_SERVER['REQUEST_URI']);

// Injection des fragments HTML (meta, header, nav, footer)
// Utilise InjectResolver pour centraliser l’injection.
$layout->injectAll();

?>

<!-- Zone principale d’affichage -->
<!-- Le contenu dynamique de la vue est injecté ici par RenderViews -->
<main>
    <?= $page_view ?>
    <!-- Ici dans l'espace administrateur je vais Injecter d'autres choses -->
</main>
