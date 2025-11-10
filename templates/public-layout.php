<?php

/**Initialisation du contrôleur de layout
 * Ce contrôleur gère les données globales (titre, thème, rôle, session)
 * et orchestre l’injection des fragments HTML dans le layout public.
 */

 $layout = new \controllers\layout\LayoutController();

/**Définition automatique du titre selon la route actuelle
 * Utilise PageTitleResolver pour éviter toute logique répétée.
*/

$layout->autoTitle($_SERVER['REQUEST_URI']);

//injection du meta
\Core\Render\Fragment::meta([
    'page_title' => $layout->getLayoutData()['page_title'],
    'theme' => $layout->getLayoutData()['theme']],
);

//injections du header
\Core\Render\Fragment::header([
    'user_connected' => $layout->getLayoutData()['user_connected'],
    'user_role' => $layout->getLayoutData()['user_role'],
    'username' => $layout->getLayoutData()['username'],
    'email' => $layout->getLayoutData()['email'],
    'theme' => $layout->getLayoutData()['theme'],
]);
?>


<main>
    <?= $page_view ?>
</main>

<?php 
//injection du footer
// \Core\Render\Fragment::footer();