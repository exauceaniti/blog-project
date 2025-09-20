<?php

/**
 * Classe Connexion
 *
 * Cette classe gère la connexion à la base de données MySQL
 * et fournit une méthode pour exécuter des requêtes sécurisées.
 */
class Connexion
{
    /**
     * @var string $host Nom de l'hôte MySQL
     */
    private $host = 'localhost';

    /**
     * @var string $user Nom d'utilisateur MySQL
     */
    private $user = 'exauce';

    /**
     * @var string $password Mot de passe MySQL
     */
    private $password = 'Mysql@.001';

    /**
     * @var string $database Nom de la base de données
     */
    private $database = 'blog_db';

    /**
     * @var PDO|null $connection Objet PDO représentant la connexion
     */
    private $connection;

    /**
     * Établir la connexion à la base de données
     *
     * Tente de se connecter à MySQL avec les informations fournies
     * et configure PDO pour lancer des exceptions en cas d'erreur.
     *
     * @return PDO Objet PDO de la connexion
     * @throws Exception Si la connexion échoue
     */
    public function connecter()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8",
                $this->user,
                $this->password
            );

            // Mode d'erreur : lancer des exceptions
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Mode de fetch par défaut : tableau associatif
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // debug : vérifier la connexion
            // var_dump($this->connection);

            return $this->connection;
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Exécuter une requête SQL préparée
     *
     * Utilise PDO pour préparer et exécuter une requête SQL avec des paramètres.
     *
     * @param string $sql La requête SQL avec des placeholders "?"
     * @param array $params Tableau des valeurs à lier aux placeholders
     * @return PDOStatement Objet PDOStatement résultant de l'exécution
     * @throws Exception Si la connexion n'existe pas ou si la requête échoue
     */
    public function executerRequete($sql, $params = [])
    {
        if (!$this->connection) {
            die("Erreur : pas de connexion à la base de données.");
        }
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
}
