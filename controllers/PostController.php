<?php

/**
 * @file postController.php
 * @description Contrôleur principal pour la gestion des articles (CRUD complet)
 * @author [Exauce Aniti]
 * @version 1.0
 * @date 2024
 *
 * @requires ../config/connexion.php
 * @requires ../models/Post.php
 * @requires ../models/User.php
 */

// ========================= INITIALISATION ET SÉCURITÉ =========================
/**
 * Démarrage de la session pour la gestion de l'authentification
 * @security Vérifie que l'utilisateur est connecté avant toute action
 */
session_start();

// 🔹 Inclusion des classes métier nécessaires au contrôleur
require_once '../config/connexion.php';
require_once '../models/Post.php';
require_once '../models/User.php';

/**
 * @var Connexion $connexion - Instance de connexion à la base de données
 * @var Post $post - Objet de gestion des articles
 * @var User $user - Objet de gestion des utilisateurs
 */
$connexion = new Connexion();
$post = new Post($connexion);
$user = new User($connexion);

// ========================= VÉRIFICATION D'AUTHENTIFICATION =========================
/**
 * Vérification que l'utilisateur est authentifié
 * @security Bloque l'accès si l'utilisateur n'est pas connecté
 * @response JSON - Retourne une erreur si non authentifié
 */
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit;
}

// ========================= GESTION DES ACTIONS =========================
/**
 * Détection de l'action demandée (POST prioritaire sur GET)
 * @var string|null $action - Action à exécuter ('ajouter', 'modifier', etc.)
 */
$action = $_POST['action'] ?? $_GET['action'] ?? null;

/**
 * Switch principal pour le routage des actions CRUD
 * @switch $action - Dirige vers la fonctionnalité correspondante
 */
switch ($action) {

    // ========================= AJOUTER UN ARTICLE AVEC MÉDIA =========================
    case 'ajouter':
        /**
         * Création d'un nouvel article avec gestion de média uploadé
         * @method POST
         * @param string $titre - Titre de l'article (obligatoire)
         * @param string $contenu - Contenu de l'article (obligatoire)
         * @param file $media - Fichier média optionnel (image/vidéo)
         *
         * @validation Vérifie les champs obligatoires et la validité du fichier
         * @security L'article est lié à l'utilisateur connecté via $_SESSION['user_id']
         *
         * @response JSON - Succès ou erreur détaillée
         */
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';
        $fichierMedia = $_FILES['media'] ?? null;

        // 🔹 Validation des données obligatoires
        if (empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "Titre ou contenu manquant"]);
            exit;
        }

        // 🔹 Validation du fichier média si présent
        if ($fichierMedia && $fichierMedia['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($fichierMedia['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["error" => "Erreur lors de l'upload du fichier"]);
                exit;
            }

            // 🔹 Validation de la taille (max 10MB)
            if ($fichierMedia['size'] > 10 * 1024 * 1024) {
                echo json_encode(["error" => "Fichier trop volumineux (max 10MB)"]);
                exit;
            }
        }

        try {
            $result = $post->ajouterArticle($titre, $contenu, $_SESSION['user_id'], $fichierMedia);

            if ($result) {
                echo json_encode(["success" => "Article ajouté avec succès"]);
            } else {
                echo json_encode(["error" => "Erreur lors de l'ajout de l'article"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    // ========================= MODIFIER UN ARTICLE AVEC MÉDIA =========================
    case 'modifier':
        /**
         * Modification d'un article existant avec option de changement de média
         * @method POST
         * @param int $id - ID de l'article à modifier (obligatoire)
         * @param string $titre - Nouveau titre (obligatoire)
         * @param string $contenu - Nouveau contenu (obligatoire)
         * @param file $media - Nouveau fichier média optionnel
         *
         * @validation Vérifie l'ID et les champs obligatoires
         * @security Vérifie les droits de modification via le modèle
         *
         * @response JSON - Succès ou erreur détaillée
         */
        $id = $_POST['id'] ?? null;
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';
        $fichierMedia = $_FILES['media'] ?? null;

        if (!$id || empty($titre) || empty($contenu)) {
            echo json_encode(["error" => "ID, titre ou contenu manquant"]);
            exit;
        }

        // 🔹 Validation du nouveau fichier média si fourni
        if ($fichierMedia && $fichierMedia['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($fichierMedia['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(["error" => "Erreur lors de l'upload du fichier"]);
                exit;
            }

            if ($fichierMedia['size'] > 10 * 1024 * 1024) {
                echo json_encode(["error" => "Fichier trop volumineux (max 10MB)"]);
                exit;
            }
        }

        try {
            $result = $post->modifierArticle($id, $titre, $contenu, $fichierMedia);

            if ($result) {
                echo json_encode(["success" => "Article modifié avec succès"]);
            } else {
                echo json_encode(["error" => "Erreur lors de la modification de l'article"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    // ========================= SUPPRIMER UN ARTICLE =========================
    case 'supprimer':
        /**
         * Suppression définitive d'un article
         * @method POST
         * @param int $id - ID de l'article à supprimer (obligatoire)
         *
         * @security Vérifie les droits de suppression via le modèle
         * @action Supprime également le média associé si existant
         *
         * @response JSON - Succès ou erreur détaillée
         */
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(["error" => "ID manquant"]);
            exit;
        }

        try {
            $result = $post->supprimerArticle($id);

            if ($result) {
                echo json_encode(["success" => "Article supprimé avec succès"]);
            } else {
                echo json_encode(["error" => "Erreur lors de la suppression de l'article"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    // ========================= VOIR TOUS LES ARTICLES =========================
    case 'voir':
        /**
         * Récupération de tous les articles (lecture seule)
         * @method GET
         *
         * @return JSON - Liste complète des articles avec leurs métadonnées
         * @format {"success": true, "articles": [{...}, {...}]}
         */
        try {
            $articles = $post->voirArticles();
            echo json_encode(["success" => true, "articles" => $articles]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    // ========================= RECHERCHER UN ARTICLE =========================
    case 'rechercher':
        /**
         * Recherche d'articles par mot-clé
         * @method GET
         * @param string $motCle - Terme de recherche (obligatoire)
         *
         * @validation Vérifie que le mot-clé n'est pas vide
         * @search Recherche dans le titre et le contenu des articles
         *
         * @return JSON - Articles correspondants à la recherche
         * @format {"success": true, "resultats": [{...}, {...}]}
         */
        $motCle = $_GET['motCle'] ?? '';

        if (empty($motCle)) {
            echo json_encode(["error" => "Mot-clé manquant"]);
            exit;
        }

        try {
            $resultats = $post->rechercherArticle($motCle);
            echo json_encode(["success" => true, "resultats" => $resultats]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
        }
        break;

    // ========================= ACTION NON RECONNUE =========================
    default:
        /**
         * Gestion des actions non reconnues
         * @response JSON - Erreur indiquant l'action invalide
         */
        echo json_encode(["error" => "Action non reconnue"]);
        break;
}

// ========================= NOTES TECHNIQUES =========================
/**
 * POINTS IMPORTANTS :
 * - Toutes les réponses sont en JSON pour une intégration front-end facile
 * - Gestion robuste des erreurs avec try/catch
 * - Validation des données côté serveur pour la sécurité
 * - Support de l'upload de médias avec limitations de taille
 * - Architecture MVC respectée
 *
 * AMÉLIORATIONS POSSIBLES :
 * - Pagination pour la vue des articles
 * - Filtres avancés pour la recherche
 * - Compression automatique des images uploadées
 * - Cache des résultats fréquents
 */
