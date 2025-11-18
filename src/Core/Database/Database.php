<?php
// core/Database/Database.php
namespace Src\Core\Database;

use PDO;
use PDOException;

/**
 * Summary of Database
 * Classe Database pour gérer la connexion à la base de données en utilisant PDO.
 * Fournit des méthodes pour obtenir la connexion, gérer les transactions et enregistrer les erreurs.
 */
class Database {

    /**
     * Summary of pdo
     * @var PDO|null
     * Instance statique de PDO -null si non initialisé
     */
    private static ?PDO $pdo = null;
    
    /**
     * Obtient l'instance de connexion à la base de données
     * Crée la connexion si elle n'existe pas encore (Lazy Loading)
     * 
     * @return PDO Instance de PDO configurée
     * @throws PDOException Si la connexion échoue
     */
    public static function getConnection(): PDO {
        //verifie si la connexion n'existe pas encore
        if (self::$pdo === null) {

            //charge le fichier de configuration de connexion
            $config = require __DIR__ . '/../../../config/connexion.php';
            
            //Creation d'une nouvelle Instance ou connexion PDO
            try {
                self::$pdo = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    ]
                );
            } catch (PDOException $e) {
                self::logError($e);
                throw new \RuntimeException("Erreur de connexion à la base de données");
            }
        }
        //Retourne l'Instance existant ou nouvellement créée
        return self::$pdo;
    }
    
    /**
     * Summary of logError
     * @param \PDOException $e
     * @return void
     * Ici je logge les erreurs de connexion à un fichier de log
     * pour faciliter le debugage sans afficher cela à l'utilisateur final.
     */
    
    private static function logError(PDOException $e): void {
        $message = "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . PHP_EOL;
        error_log($message, 3, __DIR__ . '/../../../var/logs/db_errors.log');
    }
}