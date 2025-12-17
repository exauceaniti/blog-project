<?php

namespace App\Controller;

use App\Core\Auth\Authentification;
use App\Service\UserService;
use App\Validator\UserValidator;
use App\Core\Session\FlashManager;
use App\Core\Lang\MessageBag;
use App\Entity\User;
use App\Controller\BaseController;

/**
 * Contrôleur de gestion des utilisateurs
 */
class UserController extends BaseController
{
    private UserService $userService;
    private UserValidator $validator;

    // Code IDEAL avec un Conteneur qui gère les dépendances

    // Les paramètres NE SONT PAS nullables et n'ont PAS de valeur par défaut de null
    public function __construct(UserService $userService, UserValidator $validator)
    {
        $this->userService = $userService;
        $this->validator = $validator;
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
        // 1. Redirection si déjà connecté
        if (Authentification::isLoggedIn()) {
            $this->redirect('/'); // Rediriger l'utilisateur s'il tente d'accéder à la page de connexion
            return;
        }

        $data = ['email' => '', 'password' => ''];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['email'] = trim($_POST['email'] ?? '');
            $data['password'] = $_POST['password'] ?? '';

            // 1. Validation des champs
            $errors = $this->validator::validateLogin($data);

            if (empty($errors)) {

                // 2. Authentification via Authentification (qui utilise le Service)
                $user = Authentification::login($this->userService, $data['email'], $data['password']); // <--- CORRECTION

                if ($user) {
                    // Connexion réussie : la session est gérée par Authentification::login
                    $this->handlePostLoginRedirect($user);
                    return; // Fin de l'exécution après la redirection
                } else {
                    // ÉCHEC : Message d'erreur
                    $errors['global'] = MessageBag::get('auth.failed') ?? "Email ou mot de passe incorrect.";
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
        Authentification::logout(); // Utilise la classe centrale pour la déconnexion
        session_destroy(); // Détruit la session complète (si approprié pour votre setup)

        FlashManager::info(MessageBag::get('auth.logout_success') ?? "Vous avez été déconnecté.");
        $this->redirect('/');
    }


    // Méthode privée pour encapsuler la logique de redirection post-connexion
    private function handlePostLoginRedirect(User $user): void
    {
        $targetUrl = $_SESSION['redirect_after_login']
            ?? ($user->role === 'admin' ? '/admin/dashboard' : '/');

        // On supprime la redirection intentionnelle si elle existait
        if (isset($_SESSION['redirect_after_login'])) {
            unset($_SESSION['redirect_after_login']);
        }

        // SUCCÈS : Message de bienvenue
        $welcomeMessage = MessageBag::get('auth.login_success') ?? "Connexion réussie !";
        FlashManager::success("{$welcomeMessage} Bienvenue, {$user->nom} !");

        $this->redirect($targetUrl);
    }
}
