<?php

namespace controllers\services;

use Core\BaseController;
use Core\Session\FlashManager;
use Core\Http\Redirector;
use Core\Auth\Authentification;
use models\User;
use controllers\layout\LayoutController;

require_once dirname(__DIR__, 2) . '/models/User.php';
require_once dirname(__DIR__, 2) . '/Core/Auth/Authentification.php';

class UserController extends BaseController
{
    /**
     * Affiche le formulaire de connexion ou traite la soumission
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (Authentification::login($email, $password)) {
                $role = $_SESSION['user']['role'] ?? 'user';
                $redirect = $role === 'admin' ? '/admin/dashboard' : '/profile';
                Redirector::to($redirect);
            } else {
                FlashManager::set('error', 'Email ou mot de passe incorrect.');
                Redirector::to('/login');
            }
        } else {
            $layout = new LayoutController();
            $layout->autoTitle($_SERVER['REQUEST_URI']);
            $layout->render('public/login');
        }
    }

    /**
     * Déconnecte l’utilisateur
     */
    public function logout(): void
    {
        Authentification::logout();
        FlashManager::set('success', 'Déconnexion réussie.');
        Redirector::to('/login');
    }

    /**
     * Affiche le formulaire d’inscription ou traite la soumission
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$nom || !$email || !$password) {
                FlashManager::set('error', 'Tous les champs sont obligatoires.');
                Redirector::to('/register');
                return;
            }

            $userModel = new User();
            if ($userModel->findByEmail($email)) {
                FlashManager::set('error', 'Cet email est déjà utilisé.');
                Redirector::to('/register');
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userModel->create($nom, $email, $hashedPassword);

            FlashManager::set('success', 'Compte créé avec succès. Connecte-toi maintenant.');
            Redirector::to('/login');
        } else {
            $layout = new LayoutController();
            $layout->autoTitle($_SERVER['REQUEST_URI']);
            $layout->render('public/register');
        }
    }
}
