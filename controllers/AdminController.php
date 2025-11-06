<?php
require_once __DIR__.'/BaseController.php';

// ...existing code...
class AdminController extends BaseController
{
    private $userModel;
    private $connexion;

    public function __construct()
    {
        $this->connexion = Connexion::getInstance();
        $this->userModel = new User($this->connexion);
    }
    // ...existing code...
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $error = "Tous les champs sont obligatoires.";
            } else {
                // Utiliser le modèle déjà instancié
                $user = $this->userModel->findByEmail($email);
    
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'role' => $user['role']
                    ];
                    $this->redirectResponse('index.php?route=admin/dashboard');
                }

                $error = "Email ou mot de passe incorrect.";
            }

            $this->renderView("admin/login", [
                "error_message" => $error
            ]);
        } 

        $this->renderView("admin/login");
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
