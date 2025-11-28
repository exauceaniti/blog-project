<?php

namespace Src\Controller;

use Src\Service\UserService;
use Src\Validator\UserValidator;
use Src\Core\Session\FlashManager;
use Src\Core\Session\SessionService;
use Src\Core\Lang\MessageBag;
use Src\Entity\User;

/**
 * Contrôleur de gestion des utilisateurs
 */
class UserController extends BaseController
{
    private UserService $userService;
    private UserValidator $validator;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->validator = new UserValidator();
    }
    /**

     * Affiche et gère le formulaire d'inscription
     * Méthode : GET (affichage) et POST (traitement)
     * Validation des données, création du compte et redirection
     * @return void
     */
    public function register()
    {
        $data = ['nom' => '', 'email' => '', 'password' => ''];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération et nettoyage des données POST

            $data['nom'] = trim($_POST['nom'] ?? '');

            $data['email'] = trim($_POST['email'] ?? '');

            $data['password'] = $_POST['password'] ?? '';

            // 1. Validation des champs

            $errors = $this->validator::validate($data, true);
            if (empty($errors)) {

                // 2. Enregistrement via le Service
                if ($this->userService->register($data)) {
                    FlashManager::success(MessageBag::get('user.register_success'));
                    $this->redirect('/login');
                } else {
                    $errors['global'] = MessageBag::get('user.email_taken');
                }
            }
            $data['password'] = '';
        }

        // Rendu de la vue dans le layout public
        $this->render('user/register', [
            'errors' => $errors,
            'old' => $data
        ], 'layout/public');
    }

    /**
     * Affiche et gère le formulaire de connexion
     */
    public function login()
    {
        $data = ['email' => '', 'password' => ''];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data['email'] = trim($_POST['email'] ?? '');
            $data['password'] = $_POST['password'] ?? '';

            // 1. Validation des champs
            $errors = $this->validator::validateLogin($data);

            if (empty($errors)) {

                // Authentification via le Service
                $user = $this->userService->login($data['email'], $data['password']);

                if ($user) {
                    // Connexion réussie : Utilisation du SessionService
                    SessionService::setUser($user);

                    // Gestion de la redirection et message flash
                    $this->handlePostLoginRedirect($user);
                } else {
                    // ÉCHEC : Message d'erreur
                    $errors['global'] = MessageBag::get('auth.failed');
                }
            }
            $data['password'] = '';
        }

        // Rendu de la vue dans le layout public
        $this->render('user/login', [
            'errors' => $errors,
            'old' => $data
        ], 'layout/public');
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        SessionService::destroy();
        FlashManager::info(MessageBag::get('auth.logout_success'));
        $this->redirect('/');
    }


    // NOUVEAU : Méthode privée pour encapsuler la logique de redirection post-connexion
    private function handlePostLoginRedirect(User $user): void
    {

        $targetUrl = $_SESSION['redirect_after_login']
            ?? ($user->role === 'admin' ? '/admin/dashboard' : '/');

        // On supprime la redirection intentionnelle si elle existait
        if (isset($_SESSION['redirect_after_login'])) {
            unset($_SESSION['redirect_after_login']);
        }

        // SUCCÈS : Message de bienvenue
        $welcomeMessage = MessageBag::get('auth.login_success');
        FlashManager::success("{$welcomeMessage} Bienvenue, {$user->nom} !");

        $this->redirect($targetUrl);
    }
}
