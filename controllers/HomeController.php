<?php
class HomeController
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        require_once __DIR__ . '/../views/public/home.php';
    }
}
