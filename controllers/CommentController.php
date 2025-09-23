<?php

/**
 * @file commentaireHandler.php
 * @description Handler pour la gestion complète des commentaires (CRUD)
 * @author [Exauce Aniti]
 * @version 1.0
 * @date 2024
 *
 * @requires ../config/connexion.php
 * @requires ../models/commentaire.php
 *
 * @feature Gestion des commentaires : ajout, suppression, consultation
 * @test Mode test avec utilisateur simulé pour développement
 */

// ========================== INITIALISATION DE SESSION ==========================
/**
 * Démarrage de la session pour identification utilisateur
 * @security Nécessaire pour lier les commentaires à leur auteur
 * @note En mode test, un user_id est simulé pour le développement
 */
session_start();

// ========================== INCLUSIONS DES DÉPENDANCES ==========================
/**
 * Inclusion des fichiers nécessaires au fonctionnement
 * @require ../config/connexion.php - Gestionnaire de connexion BDD
 * @require ../models/commentaire.php - Modèle métier des commentaires
 */
require_once '../config/connexion.php';
require_once '../models/commentaire.php';

// ========================== INITIALISATION DES COMPOSANTS ==========================
/**
 * Initialisation de la connexion base de données et du modèle
 * @var Connexion $connexion - Instance de gestion de connexion
 * @var PDO $conn - Objet PDO pour les requêtes directes (si nécessaire)
 * @var commentaire $comment - Modèle de gestion des commentaires
 */
$connexion = new Connexion();
$conn = $connexion->connecter(); // Instance PDO
$comment = new commentaire($connexion);

// ========================== MODE TEST / SIMULATION ==========================
/**
 * Simulation d'utilisateur connecté pour environnement de développement
 * @debug Uniquement pour les tests - À retirer en production
 * @security En production, utiliser l'authentification réelle via $_SESSION
 *
 * @todo Supprimer cette simulation lors du passage en production
 */
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 4; // ID d'un utilisateur existant pour tests
}

// ========================== ROUTAGE DES ACTIONS ==========================
/**
 * Récupération de l'action à exécuter
 * @var string|null $action - Action demandée via POST
 * @values 'ajouter', 'supprimer', 'voir'
 */
$action = $_POST['action'] ?? null;

/**
 * Switch principal de gestion des actions commentaires
 * @switch $action - Dirige vers le traitement approprié
 */
switch ($action) {

    // ========================= AJOUTER UN COMMENTAIRE =========================
    case 'ajouter':
        /**
         * Ajout d'un nouveau commentaire à un article
         * @method POST
         * @param int $articleId - ID de l'article cible (obligatoire)
         * @param string $contenu - Texte du commentaire (obligatoire)
         *
         * @validation Vérifie la présence des paramètres obligatoires
         * @security L'utilisateur est authentifié via $_SESSION['user_id']
         * @action Appel à commentaire::ajouterCommentaire()
         *
         * @response JSON - Succès ou erreur formatée
         */
        $articleId = $_POST['articleId'] ?? null;
        $contenu = $_POST['contenu'] ?? '';

        // Validation des données obligatoires
        if (!$articleId || empty($contenu)) {
            echo json_encode(["error" => "Article ID ou contenu manquant\n"]);
            exit;
        }

        try {
            // Appel au modèle pour l'ajout en base
            $comment->ajouterCommentaire($contenu, $articleId, $_SESSION['user_id']);
            echo json_encode(["success" => "Commentaire ajouté avec succès\n"]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur lors de l'ajout: " . $e->getMessage()]);
        }
        break;

    // ========================= SUPPRIMER UN COMMENTAIRE =========================
    case 'supprimer':
        /**
         * Suppression d'un commentaire existant
         * @method POST
         * @param int $id - ID du commentaire à supprimer (obligatoire)
         *
         * @validation Vérifie la présence de l'ID
         * @security Doit vérifier que l'utilisateur est propriétaire du commentaire
         * @action Appel à commentaire::supprimerCommentaire()
         *
         * @response JSON - Confirmation de suppression ou erreur
         */
        $id = $_POST['id'] ?? null;

        // Validation de l'identifiant
        if (!$id) {
            echo json_encode(["error" => "ID manquant\n"]);
            exit;
        }

        try {
            // Appel au modèle pour la suppression
            $comment->supprimerCommentaire($id);
            echo json_encode(["success" => "Commentaire supprimé avec succès\n"]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur lors de la suppression: " . $e->getMessage()]);
        }
        break;

    // ========================= VOIR LES COMMENTAIRES D'UN ARTICLE =========================
    case 'voir':
        /**
         * Consultation des commentaires associés à un article
         * @method POST
         * @param int $articleId - ID de l'article (obligatoire)
         *
         * @validation Vérifie la présence de l'ID article
         * @action Appel à commentaire::voirCommentaires()
         *
         * @return JSON - Liste des commentaires avec métadonnées
         * @format {
         *     "success": true,
         *     "commentaires": [
         *         {"id": 1, "contenu": "Texte", "auteur": "Nom", "date": "..."},
         *         ...
         *     ]
         * }
         */
        $articleId = $_POST['articleId'] ?? null;

        // Validation de l'identifiant article
        if (!$articleId) {
            echo json_encode(["error" => "Article ID manquant\n"]);
            exit;
        }

        try {
            // Récupération des commentaires depuis le modèle
            $commentaires = $comment->voirCommentaires($articleId);
            echo json_encode([
                "success" => true,
                "commentaires" => $commentaires
            ]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Erreur lors de la récupération: " . $e->getMessage()]);
        }
        break;

    // ========================= ACTION NON RECONNUE =========================
    default:
        /**
         * Gestion des actions invalides ou non spécifiées
         * @response JSON - Message d'erreur standardisé
         */
        echo json_encode(["error" => "Action non reconnue\n"]);
        break;
}

// ========================== NOTES TECHNIQUES ==========================
/**
 * CARACTÉRISTIQUES DU HANDLER :
 * - Architecture REST-like avec responses JSON
 * - Gestion d'erreurs basique (à améliorer avec try/catch)
 * - Mode test intégré pour développement
 * - Interface simple pour le front-end
 *
 * AMÉLIORATIONS RECOMMANDÉES :
 * - [ ] Ajouter la vérification des droits (propriétaire du commentaire)
 * - [ ] Implémenter la pagination pour les commentaires
 * - [ ] Ajouter la modération des commentaires
 * - [ ] Implémenter l'édition des commentaires
 * - [ ] Ajouter les réponses aux commentaires (threads)
 * - [ ] Sanitization avancée du contenu
 *
 * SÉCURITÉ À RENFORCER :
 * - [ ] Vérification CSRF pour les actions POST
 * - [ ] Validation plus poussée des inputs
 * - [ ] Limitation du taux de requêtes (rate limiting)
 * - [ ] Audit log des actions sensibles
 */
