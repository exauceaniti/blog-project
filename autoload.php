<?php
declare(strict_types=1);

/**
 * Autoload maison (PSR-4 simplifié)
 * ---------------------------------
 * Permet de charger automatiquement les classes
 * depuis le dossier /src
 */

spl_autoload_register(function (string $class) {
    // Namespace de base
    $prefix = 'Src\\';
    $baseDir = __DIR__ . '/src/';

    // Vérifie si la classe utilise le namespace "Src\"
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Récupère le chemin relatif
    $relativeClass = substr($class, $len);

    // Remplace les \ par / et ajoute .php
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // Charge le fichier si trouvé
    if (file_exists($file)) {
        require $file;
    }
});
