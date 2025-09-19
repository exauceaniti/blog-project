<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Post.php';

$conn = new Connexion();
$post = new Post($conn);
$articles = $post->voirArticle();

foreach ($articles as $article) {
    echo "<h2>{$article['titre']}</h2>";
    echo "<p>Par {$article['auteur']} le {$article['datePublication']}</p>";
    echo "<p>{$article['contenu']}</p>";
}
