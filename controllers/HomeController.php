<?php

use Core\BaseController;

require_once __DIR__.'/BaseController.php';
require_once dirname(__DIR__).'/models/Post.php';

class HomeController extends BaseController
{

    private Post $post;

    public function __construct($db)
    {
        $this->post = new Post($db);
    }

    public function index()
    {
        $articles = $this->post->getAllArticles();

        $this->renderView("public/home", 
        [
            "articles" => $articles
        ], "public/public-layout");
    }

     public function articles ()
    {

        $articles = $this->post->getAllArticles();

        $this->renderView("public/articles", [
            "articles_list" => $articles
        ], template: "public/public-custom");
    }

    public function adminConnect () : void 
    {
        $this->renderView(viewPath: "admin/login", params: [], template: "admin/login");
    }
}
