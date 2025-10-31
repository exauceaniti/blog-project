<?php

/**
 * Classe Connexion
 * Gère la connexion à la base de données MySQL avec PDO
 */
class Connexion
{
    private string $host = 'localhost';
    private string $user = 'exauce';
    private string $password = 'Mysql@.001';
    private string $database = 'blog_db';

    private static ?PDO $connection = null;

    /**
     * Établit une connexion PDO persistante
     */
    public function connecter(): PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->database};charset=utf8",
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

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            $instance = new self();
            self::$connection = $instance->connecter();
        }
        return self::$connection;
    }


    /**
     * Ferme la connexion (utile en cas de surcharge)
     */
    public function fermer(): void
    {
        self::$connection = null;
    }
}
