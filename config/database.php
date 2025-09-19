<?php
class Connexion
{
    private $host = 'localhost';
    private $user = 'exauce';
    private $password = 'Mysql@.001';
    private $database = 'blog_db';
    private $connection;

    public function connecter()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8",
                $this->user,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->connection;
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function executerRequete($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Erreur d'exécution de requête : " . $e->getMessage());
        }
    }

    public function deconnecter()
    {
        $this->connection = null;
    }

    public function getLastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
