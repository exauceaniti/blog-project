<?php

namespace Src\Controller;

use Src\Service\UserService;
use Src\Validator\UserValidator;
use Src\Core\Session\FlashManager;
use Src\Core\Http\Redirector;
use Src\Core\Lang\MessageBag;
use Src\Controller\BaseController;

/**
 * Contrôleur utilisateur - Gère l'authentification et le profil
 * 
 * RESPONSABILITÉS :
 * - Authentification (login/logout)
 * - Inscription des nouveaux utilisateurs
 * - Gestion du profil utilisateur
 * - Redirections contextuelles
 * 
 * FLOW TYPIQUE :
 * 1. Validation des données → 2. Appel Service → 3. Gestion Session → 4. Redirection
 * 
 * @package Src\Controller
 */
class UserController extends BaseController
{
    /**
     * Service de gestion des utilisateurs
     * @var UserService
     */
    private UserService $userService;

    /**
     * Constructeur avec injection de dépendance
     * 
     * @param UserService $userService Service utilisateur injecté
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Affiche ou traite le formulaire de connexion
     * 
     * FLOW :
     * GET → Affiche le formulaire
     * POST → Valide → Authentifie → Redirige
     * 
     * @return void
     * 
     * @example
     * // Connexion réussie (user)
     * → Redirection vers /profile ou URL précédente
     * 
     * // Connexion réussie (admin)  
     * → Redirection vers /admin/dashboard
     * 
     * // Échec connexion
     * → Message d'erreur + retour formulaire
     */
    public function login(): void
    {
        // 📝 AFFICHAGE DU FORMULAIRE
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('user/login', [], 'layout/public');
            return;
        }

        // 🔐 TRAITEMENT DE LA CONNEXION
        $data = $_POST;

        // 🛡️ VALIDATION
        $errors = UserValidator::validateLogin($data);
        if (!empty($errors)) {
            FlashManager::error(MessageBag::get('form.invalid'));
            Redirector::back();
            return;
        }

        // 🔑 AUTHENTIFICATION
        $user = $this->userService->login($data['email'], $data['password']);

        if ($user) {
            // ✅ CONNEXION RÉUSSIE
            session_regenerate_id(true); // Sécurité
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role;
            $_SESSION['user_name'] = $user->username; // 👈 IMPORTANT pour l'affichage

            FlashManager::success(MessageBag::get('auth.login_success'));

            // 🧭 REDIRECTION INTELLIGENTE
            if ($user->role === 'admin') {
                Redirector::to('/admin/dashboard');
            } else {
                $redirectUrl = $_SESSION['redirect_after_login'] ?? '/profile';
                unset($_SESSION['redirect_after_login']);
                Redirector::to($redirectUrl);
            }
        } else {
            // ❌ ÉCHEC AUTHENTIFICATION
            FlashManager::error(MessageBag::get('auth.failed'));
            Redirector::back();
        }
    }

    /**
     * Affiche ou traite le formulaire d'inscription
     * 
     * FLOW :
     * GET → Affiche le formulaire  
     * POST → Valide → Crée utilisateur → Redirige vers login
     * 
     * @return void
     */
    public function register(): void
    {
        // 📝 AFFICHAGE DU FORMULAIRE
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('user/register', [], 'layout/public');
            return;
        }

        // 👤 TRAITEMENT DE L'INSCRIPTION
        $data = $_POST;

        // 🛡️ VALIDATION
        $errors = UserValidator::validate($data);
        if (!empty($errors)) {
            FlashManager::error(MessageBag::get('form.invalid')); // 👈 CORRIGÉ : 'form.invalid' au lieu de 'fort.invalid'
            Redirector::back();
            return;
        }

        // 📝 CRÉATION UTILISATEUR
        $success = $this->userService->register($data);

        if ($success) {
            // ✅ INSCRIPTION RÉUSSIE
            FlashManager::success(MessageBag::get('user.register_success'));
            Redirector::to('/login');
        } else {
            // ❌ EMAIL DÉJÀ UTILISÉ
            FlashManager::error(MessageBag::get('user.email_taken'));
            Redirector::back();
        }
    }

    /**
     * Déconnecte l'utilisateur et nettoie la session
     * 
     * @return void
     */
    public function logout(): void
    {
        // 🧹 NETTOYAGE SESSION
        session_unset();
        session_destroy();

        // 👋 MESSAGE DE DÉCONNEXION
        FlashManager::success(MessageBag::get('auth.logout_success'));
        Redirector::to('/login');
    }

    /**
     * Affiche le profil de l'utilisateur connecté
     * 
     * SÉCURITÉ :
     * - Vérifie que l'user est connecté
     * - Récupère ses infos depuis la BDD
     * - Affiche uniquement si trouvé
     * 
     * @return void
     */
    public function profile(): void
    {
        // 🔐 VÉRIFICATION AUTHENTIFICATION
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            FlashManager::error(MessageBag::get('auth.required'));
            $this->render('errors/unauthorized', [], 'layout/public');
            return;
        }

        // 👤 RÉCUPÉRATION PROFIL
        $user = $this->userService->getUserById($userId);
        if (!$user) {
            FlashManager::error(MessageBag::get('user.not_found'));
            Redirector::to('/login');
            return;
        }

        // 📊 AFFICHAGE PROFIL
        $this->render('user/profile', [
            'user' => $user,
            'user_connected' => true, // 👈 IMPORTANT pour header/footer
            'user_role' => $_SESSION['role'] ?? null,
            'user_name' => $_SESSION['user_name'] ?? $user->username
        ], 'layout/public');
    }
}
