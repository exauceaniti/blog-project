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

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Affiche le formulaire de connexion ou traite la soumission
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Affiche la vue login
            $this->render('user/login', [], 'layout/public');
            return;
        }

        // Traitement POST
        $data = $_POST;
        $errors = UserValidator::validateLogin($data);

        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        $user = $this->userService->login($data['email'], $data['password']);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role;

            FlashManager::success(MessageBag::get('auth.login_success'));

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
     * Affiche le formulaire d’inscription ou traite la soumission
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Affiche la vue register
            $this->render('user/register', [], 'layout/public');
            return;
        }

        // Traitement POST
        $data = $_POST;
        $errors = UserValidator::validate($data);

        if (!empty($errors)) {
            FlashManager::error(implode('<br>', $errors));
            Redirector::back();
            return;
        }

        $success = $this->userService->register($data);

        if ($success) {
            FlashManager::success(MessageBag::get('user.register_success'));
            Redirector::to('/login');
        } else {
            FlashManager::error(MessageBag::get('user.email_taken'));
            Redirector::back();
        }
    }

    
    public function logout(): void
    {
        session_unset();
        session_destroy();
        FlashManager::success(MessageBag::get('auth.logout_success'));
        Redirector::to('/login');
    }

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

        $this->render('user/profile', ['user' => $user], 'layout/public');
    }
}
