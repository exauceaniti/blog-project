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
    private PDO|null $connection = null;

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
     * Exécuter une requête SQL préparée et sécurisée
     *
     * @param string $sql    La requête SQL (avec éventuellement des placeholders "?")
     * @param array  $params Tableau des valeurs à lier aux placeholders
     *
     * @return PDOStatement  Objet résultant de l'exécution de la requête
     *
     * @throws PDOException  Si une erreur survient lors de la préparation ou de l'exécution
     */
    public function executerRequete($sql, $params = [])
    {
        // Vérifie la connexion, sinon la crée
        if (!$this->connection) {
            $this->connecter();
        }

        try {
            // Prépare la requête
            $stmt = $this->connection->prepare($sql);

            // Exécute la requête avec les paramètres
            $stmt->execute($params);

            // Retourne l'objet PDOStatement
            return $stmt;
        } catch (PDOException $e) {
            // Arrête le script en cas d'erreur SQL et affiche le message
            die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
}
