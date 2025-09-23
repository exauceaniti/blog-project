<?php

/**
 * @file UserController.php
 * @description Contrôleur dédié à la gestion de l'authentification utilisateur
 * @author [Exauce Aniti]
 * @version 1.0
 * @date 2024
 *
 * @requires ../config/connexion.php
 * @requires ../models/User.php
 *
 * @security Gestion complète des sessions et protection des accès
 */

// Initialisation de la session pour la persistance de l'authentification
session_start();

// 🔹 Inclusion des dépendances avec chemin absolu pour plus de sécurité
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/User.php';

/**
 * CLASSE PRINCIPALE : UserController
 * @class UserController
 * @description Gère toutes les opérations d'authentification (login, logout, redirections)
 * @property User $user - Instance du modèle User pour les opérations métier
 */
class UserController
{
    /**
     * @var User $user - Instance du modèle utilisateur pour interagir avec la BDD
     * @access private
     */
    private $user;

    // ========================= CONSTRUCTEUR =========================
    /**
     * Constructeur de la classe
     * @constructor
     * @param Connexion $connexion - Instance de connexion à la base de données
     *
     * @action Initialise l'instance User pour les opérations d'authentification
     */
    public function __construct($connexion)
    {
        $this->user = new User($connexion);
    }

    // ========================= MÉTHODE DE CONNEXION =========================
    /**
     * Authentification d'un utilisateur
     * @method login
     * @param string $email - Email de l'utilisateur (format validé)
     * @param string $password - Mot de passe en clair (sera hashé)
     *
     * @validation Vérifie que les champs ne sont pas vides après trim()
     * @security Stocke les infos utilisateur en session en cas de succès
     * @session user_id, email, role
     *
     * @return array - Résultat de l'authentification
     * @format [
     *     'success' => bool,
     *     'message' => string (si erreur),
     *     'role' => string (si succès)
     * ]
     */
    public function login($email, $password)
    {
        // 🔹 Nettoyage des données d'entrée
        $email = trim($email);
        $password = trim($password);

        // Validation des champs obligatoires
        if (empty($email) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Email ou mot de passe manquant !'
            ];
        }

        // Tentative d'authentification via le modèle
        $result = $this->user->seConnecter($email, $password);

        if ($result) {
            // Connexion réussie - Initialisation de la session
            $_SESSION['user_id'] = $result['id'];          // ID unique de l'utilisateur
            $_SESSION['email'] = $result['email'];         // Email pour affichage
            $_SESSION['role'] = $result['role'] ?? 'user'; // Rôle avec valeur par défaut

            return [
                'success' => true,
                'role' => $_SESSION['role']  // Retourne le rôle pour la redirection
            ];
        }

        // Échec de l'authentification
        return [
            'success' => false,
            'message' => 'Email ou mot de passe incorrect !'
        ];
    }

    // ========================= MÉTHODE DE DÉCONNEXION =========================
    /**
     * Déconnexion sécurisée de l'utilisateur
     * @method logout
     * @security Supprime complètement la session et redirige vers le login
     * @action session_unset() + session_destroy() + redirection HTTP
     */
    public function logout()
    {
        // Nettoyage complet de la session
        session_unset();    // Supprime toutes les variables de session
        session_destroy();  // Détruit la session côté serveur

        // Redirection vers la page de login
        header('Location: ../views/login.php');
        exit; // Important pour stopper l'exécution après redirection
    }

    // ========================= GESTIONNAIRE DE REQUÊTES =========================
    /**
     * Routeur principal des actions d'authentification
     * @method handleRequest
     * @description Dirige vers la méthode appropriée selon l'action POST
     *
     * @action Analyse $_POST['action'] et exécute la fonction correspondante
     * @scenario 'connexion' → login() + redirection
     * @scenario 'deconnexion' → logout()
     *
     * @security Gère les erreurs et les messages de feedback utilisateur
     */
    public function handleRequest()
    {
        // Récupération de l'action demandée
        $action = $_POST['action'] ?? null;

        switch ($action) {
            // ================= CONNEXION UTILISATEUR =================
            case 'connexion':
                /**
                 * Traitement de la tentative de connexion
                 * @process
                 * 1. Appel de la méthode login() avec les credentials
                 * 2. Redirection selon le rôle (admin/user)
                 * 3. Gestion des erreurs avec messages en session
                 */
                $login = $this->login($_POST['email'], $_POST['password']);

                if ($login['success']) {
                    // Redirection selon le rôle de l'utilisateur
                    $redirectUrl = ($login['role'] === 'admin')
                        ? '../admin/dashboard.php'
                        : '../index.php';

                    header('Location: ' . $redirectUrl);
                } else {
                    //Stockage des erreurs pour affichage dans le formulaire
                    $_SESSION['error_message'] = $login['message'];
                    $_SESSION['form_data']['email'] = $_POST['email']; // Pré-remplissage email

                    header('Location: ../views/login.php?error=1');
                }
                exit; //Toujours exit après header('Location')

                // ================= DÉCONNEXION UTILISATEUR =================
            case 'deconnexion':
                /**
                 * Déconnexion et nettoyage de session
                 * @action Appel direct de logout() qui gère la redirection
                 */
                $this->logout();
                break;

            // ================= ACTION INCONNUE =================
            default:
                /**
                 * Action non reconnue - Redirection vers le login avec erreur
                 */
                $_SESSION['error_message'] = 'Action non reconnue';
                header('Location: ../views/login.php');
                exit;
        }
    }
}

// ========================= POINT D'ENTRÉE DU CONTROLEUR =========================
/**
 * CODE D'EXÉCUTION PRINCIPAL
 * @description Instancie et exécute le contrôleur uniquement sur les requêtes POST
 * @security N'accepte que les méthodes POST pour éviter les accès directs
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🔹 Initialisation des composants
    $connexion = new Connexion();
    $controller = new UserController($connexion);

    //Lancement du traitement de la requête
    $controller->handleRequest();
}

// ========================= NOTES DE SÉCURITÉ =========================
/**
 * ASPECTS SÉCURITÉ IMPORTANTS :
 * - Validation des entrées utilisateur
 * - Gestion sécurisée des sessions
 * - Redirections HTTP après actions critiques
 * - Nettoyage des données avec trim()
 * - Séparation des rôles admin/user
 *
 * AMÉLIORATIONS POSSIBLES :
 * - Limitation des tentatives de connexion
 * - Jetons CSRF pour les formulaires
 * - Hashage plus robuste des mots de passe
 * - Remember me functionality
 * - Audit des connexions (logs)
 */
