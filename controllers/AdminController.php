<?php
// ...existing code...
class AdminController
{
    private $userModel;
    private $connexion;

    public function __construct()
    {
        $this->connexion = new Connexion();
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
                require __DIR__ . '/../views/admin/login.php';
                return;
            }

            // Utiliser le modèle déjà instancié
            $user = $this->userModel->findByEmail($email);


            
            if ($user && password_verify($password, $user['password'])) {
                die(var_dump($user));
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'role' => $user['role']
                ];
                header('Location: index.php?route=admin/dashboard');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
                require __DIR__ . '/../views/admin/login.php';
                return;
            }
        } else {
            require __DIR__ . '/../views/admin/login.php';
        }
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
