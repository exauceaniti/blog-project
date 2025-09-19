<?php
// Dans ce fichier je vais tester la connexion a la base de donnee

require_once __DIR__ . "/config/database.php";

$db = new Connexion();
$conn = $db->connecter();

if ($conn) {
    echo "Vous êtes bien connecté à votre base de données. " . "\n";
} else {
    echo "Pas moyen de vous connecter a la base de donnee. " . "\n";
}
