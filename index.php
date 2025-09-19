<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Post.php';

//Instanciation de la connexion a la base de donnees
$conn = new Connexion();
$conn->connecter();
$post = new Post($conn);

//Instanciation de la classe Post
$post = new Post($conn);

//Recuperation des articles
$articles = $post->voirArticle();


//Afffichage des articles
foreach ($articles as $article) {
    echo "<h2>{$article['titre']}</h2>";
    echo "<p>Par {$article['auteur']} le {$article['datePublication']}</p>";
    echo "<p>{$article['contenu']}</p>";
}
