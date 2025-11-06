<?php


abstract class BaseController {

    protected function renderView (string $viewPath, array $params = [], ?string $template = null) : void 
    {

        extract($params);

        $path = dirname(__DIR__) . "/views/{$viewPath}.php";

        if ($template !== null) {
            $pathTemplate = dirname(__DIR__) . "/views/{$template}.php";

            ob_start();
            include $path;

            $content = ob_get_clean();


            extract([
                "page_view" => $content
            ]);

            ob_start();
            include $pathTemplate;

            $page = ob_get_clean();

            exit($page);
        } else {
            require $path;
        }

        exit();
    }

    protected function redirectResponse (string $url) : void 
    {
        header("Location: $url");
        exit();
    }
}