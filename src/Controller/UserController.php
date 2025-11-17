<?php
namespace Src\Controller;

use Src\Service\UserService;
use Src\Validator\UserValidator;
use Src\Core\Session\FlashManager;
use Src\Core\Http\Redirector;
use Src\Core\Lang\MessageBag;
use Src\Controller\BaseController;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Inscription d’un nouvel utilisateur
     */
    public function register(): void
    {
        $data = $_POST;

        // Validation des champs
        $errors = UserValidator::validate($data);
        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        // Vérification métier (email déjà utilisé)
        $success = $this->userService->register($data);

        if ($success) {
            FlashManager::success(MessageBag::get('user.register_success'));
            Redirector::to('/login');
        } else {
            FlashManager::error(MessageBag::get('user.email_taken'));
            Redirector::back();
        }
    }

    /**
     * Connexion utilisateur
     */
    public function login(): void
    {
        $data = $_POST;

        // Validation des champs
        $errors = UserValidator::validateLogin($data);
        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        // Authentification
        $user = $this->userService->login($data['email'], $data['password']);

        if ($user) {
            // Sécuriser la session
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role;

            FlashManager::success(MessageBag::get('auth.login_success'));

            // Redirection selon le rôle
            if ($user->role === 'admin') {
                Redirector::to('/admin/dashboard');
            } else {
                $redirectUrl = $_SESSION['redirect_after_login'] ?? '/profile';
                unset($_SESSION['redirect_after_login']);
                Redirector::to($redirectUrl);
            }
        } else {
            FlashManager::error(MessageBag::get('auth.failed'));
            Redirector::back();
        }
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout(): void
    {
        // Détruire la session proprement
        session_unset();
        session_destroy();

        FlashManager::success(MessageBag::get('auth.logout_success'));
        Redirector::to('/login');
    }

    /**
     * Affiche le profil utilisateur connecté
     */
    public function profile(): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            FlashManager::error(MessageBag::get('auth.required'));
            $this->render('errors/unauthorized', [], 'layout/public');
            return;
        }

        $user = $this->userService->getUserById($userId);

        if (!$user) {
            FlashManager::error(MessageBag::get('user.not_found'));
            Redirector::to('/login');
            return;
        }

        // Rendu de la vue profil
        $this->render('user/profile', ['user' => $user], 'layout/public');
    }


        // Exemple de rendu (si tu utilises RenderViews via BaseController)
        // $this->render('user/profile', ['user' => $user], 'layouts/user');
    
}
