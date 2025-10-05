<?php
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/commentaire.php';
require_once __DIR__ . '/../config/validator.php';



class PostController
{
    private $postModel;
    private $commentModel;
    private $validator;

    public function __construct($connexion)
    {
        $this->postModel = new Post($connexion);
        $this->commentModel = new Commentaire($connexion);
        $this->validator = new Validator();
    }

    /**
     * 🔹 Fonction de création d’un article avec validation des données
     */
    public function create()
    {
        // 1. Je vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo "Vous devez être connecté pour publier un article.";
            return;
        }

        // 2. Je nettoyer et récupérer les données du formulaire
        $titre = htmlspecialchars(strip_tags(trim($_POST['titre'] ?? '')));
        $contenu = htmlspecialchars(strip_tags(trim($_POST['contenu'] ?? '')));
        $auteurId = $_SESSION['user_id'] ?? null;
        $media = $_FILES['media'] ?? null;

        // 3 je valider les données avec la classe Validator
        $errors = $this->validator->validateArticleData($titre, $contenu, $auteurId, $media);

        // 4 Si des erreurs existent → les afficher et arrêter
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p> \n";
            }
            return;
        }

        // 5 je Préparer les variables pour l’insertion
        $mediaPath = null;
        $mediaType = null;

        // 6 Si un fichier média est présent et valide
        if ($media && $media['error'] === UPLOAD_ERR_OK) {

            // Sécuriser le nom du fichier
            $safeName = $this->validator->sanitizeFileName($media['name']);

            // Générer un nom unique pour éviter les collisions
            $uniqueName = $this->validator->generateUniqueFileName($safeName);

            // Définir le chemin complet de stockage
            $uploadDir = __DIR__ . '../assets/uploads/';
            $mediaPath = $uploadDir . $uniqueName;
            $mediaType = $media['type'];

            // Créer le dossier de stockage si il n’existe pas
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Déplacer le fichier dans le dossier des uploads
            if (!move_uploaded_file($media['tmp_name'], $mediaPath)) {
                echo "Erreur lors de l’upload du fichier.";
                return;
            }

            // Chemin à enregistrer dans la base de données (côté web)
            $mediaPath = '/assets/uploads/' . $uniqueName;
        }

        // 7 J'Insérer les données dans la base
        $result = $this->postModel->ajouterArticle($titre, $contenu, $auteurId, $mediaPath, $mediaType);

        // 8 Je Vérifier si l’insertion a réussi
        echo $result ? "<p style='color:green;'>Article publié avec succès </p>" :
            "<p style='color:red;'>Une erreur s’est produite lors de la publication.</p>";

        // 9 Je rediriger vers la page principale
        header('Location: index.php');
    }



    /**
     * Met à jour un article existant
     * @method update
     * @param int $id
     * 
     * Étapes :
     * 1R écupérer les nouvelles données envoyées par le formulaire
     * 2 Nettoyer les données (trim, strip_tags, htmlspecialchars)
     * 3 Valider les données avec la classe Validator
     * 4 Gérer le média (si un nouveau fichier a été uploadé)
     * 5 Appeler la méthode du modèle pour mettre à jour dans la base
     * 6 Rediriger ou retourner un message de confirmation
     */
    public function update($id)
    {
        // 1 Importer ton modèle et ton validateur
        global $connexion;
        $postModel = new Post($connexion);
        $validator = new Validator();

        // 2 Récupération et nettoyage des données du formulaire
        $titre = htmlspecialchars(strip_tags(trim($_POST['titre'] ?? '')));
        $contenu = htmlspecialchars(strip_tags(trim($_POST['contenu'] ?? '')));
        $auteurId = $_SESSION['user_id'] ?? null;

        // Variables média (facultatives)
        $mediaPath = null;
        $mediaType = null;

        // 3 Validation basique des champs texte
        $errors = [];

        if ($validator->isEmpty($titre)) {
            $errors[] = "Le titre ne peut pas être vide.";
        } elseif (!$validator->hasMinLength($titre, 3)) {
            $errors[] = "Le titre doit contenir au moins 3 caractères.";
        }

        if ($validator->isEmpty($contenu)) {
            $errors[] = "Le contenu ne peut pas être vide.";
        } elseif (!$validator->hasMinLength($contenu, 10)) {
            $errors[] = "Le contenu doit contenir au moins 10 caractères.";
        }

        // 4 Gestion du média (si un fichier a été uploadé)
        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['media'];

            // Vérifier le type MIME autorisé (exemple : image/png, image/jpeg)
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
            if (!$validator->isValidFileType($file, $allowedTypes)) {
                $errors[] = "Type de fichier non autorisé.";
            }

            // Vérifier la taille max (ex. 2 Mo)
            if (!$validator->isValidFileSize($file, 10 * 1024 * 1024)) {
                $errors[] = "Le fichier est trop volumineux (max : 10 Mo).";
            }

            // Si tout est bon, on nettoie le nom du fichier et on le sauvegarde
            if (empty($errors)) {
                $fileName = $validator->sanitizeFileName($file['name']);
                $uniqueName = $validator->generateUniqueFileName($fileName);
                $uploadPath = __DIR__ . '/../uploads/' . $uniqueName;

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $mediaPath = 'uploads/' . $uniqueName;
                    $mediaType = mime_content_type($uploadPath);
                } else {
                    $errors[] = "Erreur lors du téléchargement du fichier.";
                }
            }
        }

        // 5 Si des erreurs existent, on arrête ici
        if (!empty($errors)) {
            // Tu peux les stocker dans la session pour les afficher ensuite
            $_SESSION['errors'] = $errors;
            header("Location: /admin/edit.php?id=" . $id);
            exit;
        }

        // 6 Si tout est valide, on met à jour dans la base
        $result = $postModel->modifierArticle($id, $titre, $contenu, $auteurId, $mediaPath, $mediaType);

        // 7 Redirection ou message de confirmation
        // if ($result) {
        //     $_SESSION['success'] = "L’article a bien été mis à jour.";
        //     header("Location: index.php");
        // } else {
        //     $_SESSION['errors'] = ["Une erreur est survenue lors de la mise à jour."];
        //     header("Location: /admin/edit.php?id=" . $id);
        // }

        // exit;
    }














    /**
     * delete($id)
     * → Supprime un article par son ID.
     *   - Appelle $this->postModel->supprimerArticle($id)
     *   - Gère la redirection ou message de confirmation
     */

    /**
     * show($id)
     * → Affiche un article spécifique avec ses commentaires
     *   - Appelle $this->postModel->voirArticle($id)
     *   - Transmet les données à la vue
     */

    /**
     * index()
     * → Liste tous les articles
     *   - Appelle $this->postModel->getAllArticles()
     *   - Transmet les données à la vue principale
     */

    /**
     * search($motCle)
     * → Recherche des articles selon un mot-clé
     *   - Appelle $this->postModel->rechercherArticle($motCle)
     */

    /**
     * byAuthor($auteurId)
     * → Récupère les articles d’un auteur précis
     */

    /**
     * count() et countByAuthor($auteurId)
     * → Compte les articles totaux ou ceux d’un auteur pour statistiques/dashboard
     */

    /**
     * paginate($page, $limit)
     * → Récupère les articles page par page avec OFFSET/LIMIT
     */
}
