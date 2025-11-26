<?php

namespace Src\Controller;

use Src\Service\UserService;
use Src\Validator\UserValidator;
use Src\Core\Session\FlashManager;
use Src\Core\Lang\MessageBag;
// ATTENTION : On n'importe plus FlashManager ni Redirector, on utilise les méthodes du BaseController

class UserController extends BaseController
{
    private UserService $userService;
    private UserValidator $validator;

    public function __construct()
    {
        // Initialisation des dépendances
        $this->userService = new UserService();
        $this->validator = new UserValidator();
    }

    /**
     * Affiche et gère le formulaire d'inscription.
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

                    // SUCCÈS : Utilisation de la méthode FlashManager::success (héritée ou accédée via la composition)
                    FlashManager::success(MessageBag::get('user.register_success'));


                    $this->redirect('/login');
                } else {
                    // ERREUR : Email déjà utilisé
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
     * Affiche et gère le formulaire de connexion.
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

                // 2. Authentification via le Service
                $user = $this->userService->login($data['email'], $data['password']);

                if ($user) {

                    // Connexion réussie : Stockage Session
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_role'] = $user->role;
                    $_SESSION['user_nom'] = $user->nom;

                    // 3. Logique de Redirection Intentionnelle
                    if (isset($_SESSION['redirect_after_login'])) {
                        $target_url = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                    } else {
                        // Redirection par défaut : Admin vers Dashboard, User vers Accueil
                        $target_url = ($user->role === 'admin') ? '/admin/dashboard' : '/';
                    }

                    // ✅ SUCCÈS : Message de bienvenue
                    $welcomeMessage = MessageBag::get('auth.login_success');
                    FlashManager::success("{$welcomeMessage} Bienvenue, {$user->nom} !");

                    // 🚀 REDIRECTION PROPRE : Utilisation de $this->redirect()
                    $this->redirect($target_url);
                } else {
                    // ÉCHEC : Message d'erreur
                    $errors['global'] = MessageBag::get('auth.failed');
                }
            }
            $data['password'] = '';
        }

        // 🎯 Rendu de la vue dans le layout public
        $this->render('user/login', [
            'errors' => $errors,
            'old' => $data
        ], 'layout/public'); // <-- Utilisation explicite du layout 'public'
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Détruire la session et les données
        session_unset();
        session_destroy();

        // SUCCÈS : Message de déconnexion
        FlashManager::info(MessageBag::get('auth.logout_success'));

        // REDIRECTION PROPRE : Utilisation de $this->redirect()
        $this->redirect('/');
    }
}
