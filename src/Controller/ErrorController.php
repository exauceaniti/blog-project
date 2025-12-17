<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Core\Lang\MessageBag;

class ErrorController extends BaseController
{
    /**
     * Page 404 - Not Found
     */
    public function notFound(): void
    {
        http_response_code(404);
        $this->render('errors/404', [
            'title'   => MessageBag::get('system.not_found'),
            'message' => MessageBag::get('system.error')
        ], 'layout/public');
    }

    /**
     * Page Unauthorized - accès refusé
     */
    public function unauthorized(): void
    {
        http_response_code(403);
        $this->render('errors/unauthorized', [
            'title'   => MessageBag::get('auth.unauthorized'),
            'message' => MessageBag::get('auth.required')
        ], 'layout/public');
    }
}
