<?php

/**
 * @file UserController.php
 * @description Contrôleur principal pour la gestion de l'authentification utilisateur
 * @author [Exauce Aniti]
 * @version 1.0
 * @date 2024
 * 
 * @requires ../config/connexion.php - Gestion de la connexion à la base de données
 * @requires ../models/User.php - Modèle utilisateur pour les opérations métier
 * 
 * @security Implémente une gestion sécurisée des sessions et protège contre les accès non autorisés
 */

// ========================= INITIALISATION DE LA SESSION =========================
/**
 * Démarrage de la session PHP pour maintenir l'état d'authentification
 * @important Doit être appelé avant tout envoi de contenu au navigateur
 */
session_start();

// ========================= INCLUSION DES DÉPENDANCES =========================
/**
 * Inclusion sécurisée avec chemin absolu pour éviter les problèmes de chemin relatif
 * __DIR__ donne le chemin du dossier courant (plus sûr que les chemins relatifs)
 */
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/User.php';

/**
 * CLASSE PRINCIPALE : UserController
 * @class UserController
 * @description Orchestre toutes les opérations d'authentification :
 * - Connexion (login)
 * - Déconnexion (logout) 
 * - Inscription (register)
 * - Redirections selon les rôles
 * 
 * @property User $user Instance du modèle User pour les interactions avec la base de données
 * @example $controller = new UserController($connexion);
 */
class UserController
{
    /**
     * @var User $user Instance du modèle utilisateur
     * @access private - Encapsulation pour protéger l'accès direct
     * @usage Utilisé pour toutes les opérations CRUD sur les utilisateurs
     */
    private $user;

    // ========================= CONSTRUCTEUR =========================
    /**
     * Constructeur de la classe - Initialise les dépendances
     * @constructor
     * @param Connexion $connexion Instance active de connexion à la base de données
     * 
     * @action Crée une instance du modèle User pour les opérations métier
     * @example new UserController(new Connexion());
     */
    public function __construct($connexion)
    {
        // Initialisation du modèle User avec la connexion BDD
        $this->user = new User($connexion);
    }

    // ========================= MÉTHODE DE CONNEXION =========================
    /**
     * Authentifie un utilisateur avec son email et mot de passe
     * @method login
     * @param string $email Email de l'utilisateur (sera validé)
     * @param string $password Mot de passe en clair (sera hashé et comparé)
     * 
     * @process Flow d'authentification :
     * 1. Nettoyage des données avec trim()
     * 2. Validation de la présence des champs obligatoires
     * 3. Appel du modèle User pour vérifier les credentials
     * 4. Création de la session en cas de succès
     * 
     * @validation Vérifie que email et password ne sont pas vides après trim()
     * 
     * @security En cas de succès :
     * - Stocke l'ID utilisateur en session
     * - Stocke l'email pour l'affichage
     * - Stocke le rôle pour les autorisations
     * 
     * @return array Résultat structuré de l'opération
     * @format [
     *     'success' => boolean, // true si authentification réussie
     *     'message' => string,  // Message d'erreur si échec
     *     'role' => string      // Rôle de l'utilisateur si succès
     * ]
     * 
     * @example 
     * $result = $controller->login('test@example.com', 'password123');
     * if ($result['success']) { /* Redirection selon le rôle * / }
     */
    public function login($email, $password)
    {
        // 🔹 PHASE 1 : NETTOYAGE DES DONNÉES
        // Suppression des espaces superflus pour éviter les erreurs de saisie
        $email = trim($email);
        $password = trim($password);

        // 🔹 PHASE 2 : VALIDATION DES CHAMPS OBLIGATOIRES
        if (empty($email) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Email ou mot de passe manquant !'
            ];
        }

        // 🔹 PHASE 3 : TENTATIVE D'AUTHENTIFICATION
        // Délégation au modèle User qui gère le hashage et la vérification
        $result = $this->user->seConnecter($email, $password);

        if ($result) {
            // 🔹 AUTHENTIFICATION RÉUSSIE - INITIALISATION DE LA SESSION
            $_SESSION['user_id'] = $result['id'];          // Identifiant unique de l'utilisateur
            $_SESSION['email'] = $result['email'];         // Email pour personnalisation de l'UI
            $_SESSION['role'] = $result['role'] ?? 'user'; // Rôle avec valeur par défaut 'user'

            return [
                'success' => true,
                'role' => $_SESSION['role']  // Retourne le rôle pour la redirection
            ];
        }

        // 🔹 AUTHENTIFICATION ÉCHOUÉE
        return [
            'success' => false,
            'message' => 'Email ou mot de passe incorrect !'
        ];
    }

    // ========================= MÉTHODE DE DÉCONNEXION =========================
    /**
     * Déconnecte sécuriséement l'utilisateur et nettoie la session
     * @method logout
     * 
     * @security Actions critiques :
     * - session_unset() : Supprime toutes les variables de session
     * - session_destroy() : Détruit la session côté serveur
     * - Redirection HTTP immédiate vers le login
     * - exit() pour stopper l'exécution du script
     * 
     * @action Suite d'opérations de nettoyage :
     * 1. Vider les variables de session
     * 2. Détruire la session serveur
     * 3. Rediriger vers la page de login
     * 4. Stopper l'exécution du script
     * 
     * @example $controller->logout(); // L'utilisateur est déconnecté et redirigé
     */
    public function logout()
    {
        // 🔹 PHASE 1 : NETTOYAGE DE LA SESSION
        session_unset();    // Supprime toutes les variables de session PHP
        session_destroy();  // Détruit complètement la session côté serveur

        // 🔹 PHASE 2 : REDIRECTION VERS LA PAGE DE LOGIN
        header('Location: ../views/login.php');
        exit; // 🔐 IMPÉRATIF : Arrête l'exécution après redirection
    }

    // ========================= MÉTHODE D'INSCRIPTION =========================
    /**
     * Inscrit un nouvel utilisateur dans le système
     * @method register
     * @param string $nom Nom complet de l'utilisateur
     * @param string $email Email unique de l'utilisateur
     * @param string $password Mot de passe en clair (sera hashé)
     *
     * @process Flow d'inscription :
     * 1. Nettoyage des données avec trim()
     * 2. Validation des champs obligatoires
     * 3. Vérification de l'unicité de l'email
     * 4. Hashage du mot de passe et création du compte
     *
     * @return bool True si l'inscription réussit, false si l'email existe déjà
     *
     * @example
     * $success = $controller->register('John Doe', 'john@example.com', 'password123');
     */
    public function register($nom, $email, $password)
    {
        // Nettoyage des données d'entrée
        $nom = trim($nom);
        $email = trim($email);
        $password = trim($password);

        // Validation des champs obligatoires
        if (empty($nom) || empty($email) || empty($password)) {
            $_SESSION['error_message'] = 'Tous les champs sont obligatoires !';
            return false;
        }

        // Tentative d'inscription via le modèle
        return $this->user->sInscrire($email, $password, $nom);
    }

    // ========================= GESTIONNAIRE DE REQUÊTES PRINCIPAL =========================
    /**
     * Routeur principal qui distribue les actions selon le paramètre POST
     * @method handleRequest
     * 
     * @description Analyse la requête POST et appelle la méthode appropriée :
     * - 'connexion' → Traitement de la connexion
     * - 'deconnexion' → Déconnexion de l'utilisateur  
     * - 'inscription' → Création d'un nouveau compte
     * 
     * @security Vérifications importantes :
     * - Ne fonctionne qu'avec la méthode POST
     * - Gère les actions inconnues avec redirection
     * - Stocke les messages d'erreur en session pour affichage
     * 
     * @process Pour chaque action :
     * 1. Récupération et validation des données POST
     * 2. Appel de la méthode métier appropriée
     * 3. Gestion des résultats (redirections, messages d'erreur)
     * 4. Nettoyage des données sensibles
     * 
     * @example Appelé automatiquement à la réception d'un formulaire POST
     */
    public function handleRequest()
    {
        // 🔹 RÉCUPÉRATION DE L'ACTION DEMANDÉE
        // Utilisation de l'opérateur null coalescent pour éviter les notices
        $action = $_POST['action'] ?? null;

        // 🔹 DISTRIBUTION SELON L'ACTION
        switch ($action) {
            // ================= CAS 1 : CONNEXION UTILISATEUR =================
            case 'connexion':
                /**
                 * Traite le formulaire de connexion utilisateur
                 * @process détaillé :
                 * 1. Appel de la méthode login() avec email et password
                 * 2. Si succès : redirection selon le rôle (admin → dashboard, user → index)
                 * 3. Si échec : conservation de l'email saisi et message d'erreur
                 */

                // Tentative de connexion avec les credentials POST
                $loginResult = $this->login($_POST['email'], $_POST['password']);

                if ($loginResult['success']) {
                    // 🔹 CONNEXION RÉUSSIE - REDIRECTION PAR RÔLE
                    $redirectUrl = ($loginResult['role'] === 'admin')
                        ? '../admin/dashboard.php'  // Administration
                        : '../index.php';           // Interface utilisateur standard

                    header('Location: ' . $redirectUrl);
                } else {
                    // 🔹 ÉCHEC DE CONNEXION - PRÉPARATION DES DONNÉES POUR LE FORMULAIRE
                    $_SESSION['error_message'] = $loginResult['message'];
                    $_SESSION['form_data']['email'] = $_POST['email']; // Pré-remplissage email

                    header('Location: ../views/login.php?error=1');
                }
                exit; // 🔐 IMPÉRATIF : Stop l'exécution après redirection

                // ================= CAS 2 : DÉCONNEXION UTILISATEUR =================
            case 'deconnexion':
                /**
                 * Déconnexion immédiate de l'utilisateur
                 * @note La méthode logout() gère elle-même la redirection
                 */
                $this->logout();
                break;

            case 'inscription':
                /**
                 * Traite la création d'un nouveau compte utilisateur
                 * @process détaillé :
                 * 1. Récupération et validation des données du formulaire
                 * 2. Tentative d'inscription via le modèle User
                 * 3. Si succès : connexion automatique + redirection page d'accueil
                 * 4. Si échec : message d'erreur et retour au formulaire
                 */

                // Récupération des données avec valeurs par défaut
                $nom = trim($_POST['nom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');

                // Validation des champs obligatoires
                if (empty($nom) || empty($email) || empty($password)) {
                    $_SESSION['error_message'] = 'Tous les champs sont obligatoires !';
                    $_SESSION['form_data'] = $_POST; // Conservation des données saisies
                    header('Location: ../views/register.php?error=1');
                    exit;
                }

                // Tentative d'inscription
                $inscriptionSuccess = $this->user->sInscrire($email, $password, $nom);

                if ($inscriptionSuccess) {
                    // 🔹 INSCRIPTION RÉUSSIE - CONNEXION AUTOMATIQUE
                    $loginResult = $this->login($email, $password);

                    if ($loginResult['success']) {
                        // 🔹 MESSAGE DE SUCCÈS EN SESSION POUR AFFICHAGE SUR LA PAGE D'ACCUEIL
                        $_SESSION['success_message'] = 'Félicitations ! Votre compte a été créé avec succès.';

                        // 🔹 REDIRECTION VERS LA PAGE D'ACCUEIL
                        header('Location: ../index.php?success=1');
                    } else {
                        // Cas improbable où l'inscription réussit mais la connexion échoue
                        $_SESSION['error_message'] = 'Compte créé mais problème de connexion automatique.';
                        header('Location: ../views/login.php');
                    }
                } else {
                    // 🔹 ÉCHEC - EMAIL DÉJÀ UTILISÉ
                    $_SESSION['error_message'] = 'Cet email est déjà utilisé !';
                    $_SESSION['form_data'] = $_POST; // Conservation pour correction
                    header('Location: ../views/register.php?error=1');
                }
                exit;

                // Tentative d'inscription
                $inscriptionSuccess = $this->user->sInscrire($email, $password, $nom);

                if ($inscriptionSuccess) {
                    // 🔹 INSCRIPTION RÉUSSIE - REDIRECTION VERS LOGIN
                    header('Location: ../views/register.php?success=1');
                } else {
                    // 🔹 ÉCHEC - EMAIL DÉJÀ UTILISÉ
                    $_SESSION['error_message'] = 'Cet email est déjà utilisé !';
                    $_SESSION['form_data'] = $_POST; // Conservation pour correction
                    header('Location: ../views/register.php?error=1');
                }
                exit;

                // ================= CAS PAR DÉFAUT : ACTION INCONNUE =================
            default:
                /**
                 * Gestion des actions non reconnues - Sécurité renforcée
                 * @security Empêche l'exécution de code non autorisé
                 */
                $_SESSION['error_message'] = 'Action non reconnue';
                header('Location: ../views/login.php');
                exit;
        }
    }
}

// ========================= POINT D'ENTRÉE PRINCIPAL =========================
/**
 * CODE D'EXÉCUTION - GARDIEN D'ACCÈS
 * @description Instancie et exécute le contrôleur uniquement pour les requêtes POST
 * 
 * @security Protection importante :
 * - N'accepte que les méthodes POST (formulaires)
 * - Bloque l'accès direct via URL (GET)
 * - Empêche l'exécution accidentelle du contrôleur
 * 
 * @process Flow d'exécution :
 * 1. Vérification de la méthode HTTP (doit être POST)
 * 2. Initialisation de la connexion à la BDD
 * 3. Création de l'instance du contrôleur
 * 4. Lancement du traitement de la requête
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🔹 INITIALISATION DES COMPOSANTS
    $connexion = new Connexion();                    // Connexion à la base de données
    $controller = new UserController($connexion);    // Création du contrôleur

    // 🔹 LANCEMENT DU TRAITEMENT DE LA REQUÊTE
    $controller->handleRequest();
} else {
    /**
     * Cas d'accès non autorisé (méthode GET directe)
     * @security Redirige vers le login pour éviter l'exécution non souhaitée
     */
    header('Location: ../views/login.php');
    exit;
}

// ========================= NOTES TECHNIQUES POUR LES DÉVELOPPEURS =========================
/**
 * ARCHITECTURE ET CONVENTIONS :
 * 
 * 1. STRUCTURE MVC RESPECTÉE :
 *    - Modèle (User.php) : Logique métier et accès aux données
 *    - Vue (dossier views/) : Présentation et formulaires
 *    - Contrôleur (ce fichier) : Orchestration et logique applicative
 * 
 * 2. SÉCURITÉ IMPLÉMENTÉE :
 *    - Validation des entrées utilisateur
 *    - Gestion sécurisée des sessions
 *    - Protection contre les failles XSS et injection
 *    - Redirections HTTP sécurisées
 * 
 * 3. GESTION DES ERREURS :
 *    - Messages d'erreur stockés en session
 *    - Conservation des données saisies en cas d'erreur
 *    - Redirections appropriées selon le contexte
 * 
 * 4. FLUX D'AUTHENTIFICATION :
 *    - Login → Vérification credentials → Session → Redirection rôle
 *    - Logout → Nettoyage session → Redirection login
 *    - Register → Validation → Création compte → Redirection
 * 
 * AMÉLIORATIONS FUTURES POSSIBLES :
 * 
 * 1. SÉCURITÉ AVANCÉE :
 *    - [ ] Limitation des tentatives de connexion (brute force protection)
 *    - [ ] Jetons CSRF pour les formulaires
 *    - [ ] Double authentification (2FA)
 *    - [ ] Audit des connexions (logging)
 * 
 * 2. FONCTIONNALITÉS UTILISATEUR :
 *    - [ ] Mot de passe oublié
 *    - [ ] Remember me functionality
 *    - [ ] Profil utilisateur modifiable
 *    - [ ] Avatar utilisateur
 * 
 * 3. PERFORMANCES :
 *    - [ ] Cache des sessions
 *    - [ ] Optimisation des requêtes BDD
 *    - [ ] Compression des réponses
 * 
 * 4. MAINTENABILITÉ :
 *    - [ ] Logs détaillés pour le debugging
 *    - [ ] Tests unitaires automatisés
 *    - [ ] Documentation API complète
 * 
 * @last_updated 2024
 * @maintainer [Exauce Aniti]
 * @version 1.0
 */
