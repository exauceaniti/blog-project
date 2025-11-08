<?php

//Controller metier 

use Core\BaseController;
use controllers\layout\LayoutController;

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

        $layout = new LayoutController();
        $layout->autoTitle($_SERVER['REQUEST_URI']);
        $layout->render('public/home', [
            "articles_list" => $articles
        ]);
    }

    public function articles ()
    {
        $articles = $this->post->getAllArticles();

        $layout = new LayoutController();
        $layout->autoTitle($_SERVER['REQUEST_URI']);
        $layout->render('public/articles', [
            "articles_list" => $articles
        ]);
    }



    // public function adminConnect () : void 
    // {
    //     $layout = new LayoutController();
    //     $layout->autoTitle($_SERVER['REQUEST_URI']);
    //     $layout->render('admin/login', [], 'public-layout');
    // }
}
