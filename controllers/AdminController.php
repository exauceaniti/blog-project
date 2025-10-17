<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/connexion.php';


//Classe Principale 
class AdminController
{
    private $userModel;

    public function __construct()
    {
        $connexion = new Connexion();
        $this->userModel = new User($connexion);
    }



    /**
     * Connexion admin
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login($email, $password)
    {
        $user = $this->userModel->seConnecter($email, $password);

        if ($user && $user['role'] === 'admin') {
            // Stockage info admin dans session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            return true;
        }

        return false;
    }





    /**
     * Affiche le dashboard admin
     */
    public function dashboard()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?route=admin/login');
            exit;
        }

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }




    /**
     * Déconnexion
     */
    public function logout()
    {
        session_destroy();
        header('Location: index.php?route=admin/login');
        exit;
    }
}
