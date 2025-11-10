<?php

namespace controllers;

use controllers\layout\LayoutController;

class ErrorController
{
    public function unauthorized(): void
    {
        $layout = new LayoutController();
        $layout->autoTitle('Accès refusé');
        $layout->render('public/unauthorized');
    }
}
