<?php
session_start();
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $user;

    public function __construct($connexion)
    {
        $this->user = new User($connexion);
    }

    public function login($email, $password)
    {
        $email = trim($email);
        $password = trim($password);

        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email ou mot de passe manquant !'];
        }

        $result = $this->user->seConnecter($email, $password);

        if ($result) {
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['role'] = $result['role'] ?? 'user';
            return ['success' => true, 'role' => $_SESSION['role']];
        }

        return ['success' => false, 'message' => 'Email ou mot de passe incorrect !'];
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ../views/login.php');
        exit;
    }

    public function handleRequest()
    {
        $action = $_POST['action'] ?? null;

        switch ($action) {
            case 'connexion':
                $login = $this->login($_POST['email'], $_POST['password']);
                if ($login['success']) {
                    header('Location: ' . ($login['role'] === 'admin' ? '../admin/dashboard.php' : '../index.php'));
                } else {
                    $_SESSION['error_message'] = $login['message'];
                    $_SESSION['form_data']['email'] = $_POST['email'];
                    header('Location: ../views/login.php?error=1');
                }
                exit;

            case 'deconnexion':
                $this->logout();
                break;

            default:
                $_SESSION['error_message'] = 'Action non reconnue';
                header('Location: ../views/login.php');
                exit;
        }
    }
}

// Si on poste depuis login.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connexion = new Connexion();
    $controller = new UserController($connexion);
    $controller->handleRequest();
}
