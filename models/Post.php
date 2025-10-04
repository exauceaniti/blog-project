<?php

/**
 * Classe Post
 * @class Post
 */
class Post
{
    /**
     * Constructeur de la classe Post
     * @param object $connexion Objet de connexion à la base de données
     */

    private $conn;

    /**
     * Constructeur de la classe Post
     * @param mixed $connexion
     */
    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }


    /**
     * Ajouter un nouvelle article dans la base de donnees
     * @methode ajouterArticle
     * Dans cette methode on recupere le mediaPath deja uploade par le Controller
     * @access public
     * @param string $titre
     * @param string $contenu
     * @param int $auteurId
     * @param string|null $mediaPath
     * @param string|null $mediaType
     * @return object|false
     */
    public function ajouterArticle($titre, $contenu, $auteurId, $mediaPath = null, $mediaType = null)
    {
        // Ici, on reçoit le mediaPath déjà uploadé par le Controller
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, media_path, media_type) VALUES (?, ?, ?, ?, ?)";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $auteurId, $mediaPath, $mediaType]);
    }



    /**
     * Modifie un article existant dans la base de données
     * @method modifierArticle
     * @access public
     * @param int $id
     * @param string $titre
     * @param string $contenu
     * @param int $auteurId
     * @param string|null $mediaPath
     * @param string|null $mediaType
     * @return object|false
     */
    public function modifierArticle($id, $titre, $contenu, $auteurId, $mediaPath = null, $mediaType = null)
    {
        $sql = "UPDATE articles SET titre = ?, contenu = ?, auteur_id = ?, media_path = ?, media_type = ? WHERE id = ?";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $auteurId, $mediaPath, $mediaType, $id]);

    }



    /**
     * Supprimer definitivement un article de la base de donnees
     * @method supprimerArticle
     * @access public
     * @param int $id seulement l'id de l'article a supprimer
     * @return void 
     */

    public function supprimerArticle($id): void
    {
        $sql = "DELETE FROM articles WHERE id = ?";
        $this->conn->executerRequete($sql, [$id]);
    }


    /**
     * Voir un article par son id
     * @method voirArticle
     * @access public
     * @param int $id
     * @return object|false
     */

    public function voirArticle($id)
    {
        $sql = "SELECT * FROM articles WHERE id = ?";
        return $this->conn->executerRequete($sql, [$id]);
    }


    /**
     * Voir tous les articles de la base de donees 
     * avec information sur l'auteur.
     * @method getAllArticles
     * @access public
     * @return object|false
     */

    public function getAllArticles()
    {
        $sql = "SELECT articles.id, articles.titre, articles.contenu, articles.media_path, articles.media_type, 
        auteur.nom as auteur_nom, auteur.email as auteur_email,
        FROM articles
        JOIN auteur ON articles.auteur_id = auteur.id";
        return $this->conn->executerRequete($sql);
    }


    /**
     * Rechercher un article par son mot clef ou titre
     * @method rechercherArticle
     * @access public
     * @param string $motClet
     * @return object|false
     */

    public function rechercherArticle($motClet)
    {
        $sql = "SELECT * FROM articles WHERE titre LIKE ? OR contenu LIKE ?";
        return $this->conn->executerRequete($sql, ["%$motClet%", "%$motClet%"]);
    }


    /**
     * Lire tout l'article d'un auteur
     * @method rechercherArticleParAuteur
     * @access public
     * @param int $auteurId
     * @return object|false
     */

    public function rechercherArticleParAuteur($auteurId)
    {
        $sql = "SELECT * FROM articles WHERE auteur_id = ?";
        return $this->conn->executerRequete($sql, [$auteurId]);
    }


    /**
     * compter tous les articles
     * @method countAllArticles
     * @access public
     * @return int
     */

    public function countAllArticles()
    {
        $sql = "SELECT COUNT(*) FROM articles";
        return $this->conn->executerRequete($sql)->fetchColumn();
    }


    /**
     * compter tous les articles d'un auteur
     * @method countAllArticlesParAuteur
     * @access public
     * @param int $auteurId
     * @return int
     */

    public function countAllArticlesParAuteur($auteurId)
    {
        $sql = "SELECT COUNT(*) FROM articles WHERE auteur_id = ?";
        return $this->conn->executerRequete($sql, [$auteurId])->fetchColumn();
    }



    /**
     * pagination
     * @method pagination
     * @access public
     * @param int $page
     * @return object|false
     */

    public function pagination($page)
    {
        $sql = "SELECT * FROM articles LIMIT ?, ?";
        return $this->conn->executerRequete($sql, [($page - 1) * 5, 5]);
    }




    /**
     * Je met ici tout ce qui est algorithme a utiliser dans cette classe.
     * 1- Ajouter un article (ajouterArticle) Ok
     * 2- Modifier un article (modifierArticle) Ok
     * 3- Supprimer un article (supprimerArticle) Ok
     * 4- Voir un article par son id (voirArticle) Ok
     * 5- Voir tous les articles (getAllArticles) Ok
     * 6- rechercher un article par son mot clef ou titre (rechercherArticle) prend en parametre $motClet. Ok
     * 7- Lire tout l'article d'un auteur (rechercherArticleParAuteur) prend en parametre $auteurId Ok
     * 8- compter tous les articles (countAllArticles) Ok
     * 9- compter tous les articles d'un auteur (countAllArticlesParAuteur) prend en parametre $auteurId
     * 10-pagination.
     */




}