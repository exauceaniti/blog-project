<?php

/**
 * Classe Connexion
 * Gère la connexion à la base de données MySQL avec PDO
 */

__DIR__ . '/../logs/db_errors.log';

class Connexion
{

    private static ?self $instance = null;

    private string $host = 'localhost';
    private string $user = 'exauce';
    private string $password = 'Mysql@.001';
    private string $database = 'blog_db';

    private ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function getInstance () : Connexion
    {
        if (self::$instance === null) {
            self::$instance = new Connexion();
        }
        return self::$instance;
    }

    /**
     * Établit une connexion PDO persistante
     */
    public function connecter(): PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->host}:3306;dbname={$this->database};charset=utf8mb4",
                    $this->user,
                    $this->password,
                    [
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                error_log($e->getMessage(), 3, __DIR__ . '/../logs/db_errors.log');
                throw new RuntimeException("Erreur de connexion à la base de données");
            }
        }

        return self::$connection;
    }


    /**
     * Exécute une requête SQL préparée
     */
    public function executerRequete(string $sql, array $params = []): PDOStatement
    {
        $pdo = $this->connecter();

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }


    /**
     * Ferme la connexion (utile en cas de surcharge)
     */
    public function fermer(): void
    {
        self::$connection = null;
    }
}
