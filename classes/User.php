<?php
class User
{
    private $conn;

    public function __construct($connexion)
    {
        $this->conn = $connexion;
    }

    public function seConnecter($email, $password)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->conn->executerRequete($sql, [$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        return false;
    }

    public function seDeconnecter()
    {
        session_destroy();
    }

    public function creeArticle($titre, $contenu)
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur_id, datePublication) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$titre, $contenu, $_SESSION['user_id']]);
    }

    public function creeCommentaire($contenu, $articleId)
    {
        $sql = "INSERT INTO commentaires (contenu, article_id, auteur_id, dateCommentaire) VALUES (?, ?, ?, NOW())";
        return $this->conn->executerRequete($sql, [$contenu, $articleId, $_SESSION['user_id']]);
    }
}
