<?php
namespace Src\Core\Lang;

class MessageBag {
    private static array $messages = [

        // 🔐 Authentification & Sécurité
        'auth.required' => "Vous devez être connecté pour accéder à cette page.",
        'auth.failed' => "Email ou mot de passe incorrect.",
        'auth.admin_only' => "Accès réservé aux administrateurs.",
        'auth.user_only' => "Accès réservé aux utilisateurs connectés.",
        'auth.logout_success' => "Déconnexion réussie.",
        'auth.login_success' => "Connexion réussie. Bienvenue !",
        'auth.session_expired' => "Votre session a expiré. Veuillez vous reconnecter.",
        'auth.unauthorized' => "Accès non autorisé.",
        'auth.redirected' => "Vous avez été redirigé vers la page de connexion.",

        // 👤 Utilisateur
        'user.nom_required' => "Le nom est obligatoire.",
        'user.email_invalid' => "L'adresse email est invalide.",
        'user.email_taken' => "Cette adresse email est déjà utilisée.",
        'user.password_short' => "Le mot de passe doit contenir au moins 6 caractères.",
        'user.register_success' => "Inscription réussie. Vous pouvez maintenant vous connecter.",
        'user.update_success' => "Profil mis à jour avec succès.",
        'user.delete_success' => "Utilisateur supprimé.",
        'user.not_found' => "Utilisateur introuvable.",
        'user.promoted' => "L'utilisateur a été promu administrateur.",
        'user.role_invalid' => "Rôle utilisateur invalide.",
        'user.account_required' => "Vous devez avoir un compte pour effectuer cette action.",

        // 📝 Article
        'article.title_required' => "Le titre de l'article est obligatoire.",
        'article.content_required' => "Le contenu de l'article est obligatoire.",
        'article.create_success' => "Article publié avec succès.",
        'article.update_success' => "Article mis à jour.",
        'article.delete_success' => "Article supprimé.",
        'article.not_found' => "Article introuvable.",
        'article.slug_exists' => "Un article avec ce titre existe déjà.",
        'article.comment_disabled' => "Les commentaires sont désactivés pour cet article.",

        // 💬 Commentaire
        'comment.content_required' => "Le contenu du commentaire est obligatoire.",
        'comment.add_success' => "Commentaire ajouté avec succès.",
        'comment.delete_success' => "Commentaire supprimé.",
        'comment.not_found' => "Commentaire introuvable.",
        'comment.auth_required' => "Vous devez être connecté pour commenter.",
        'comment.too_short' => "Le commentaire est trop court.",
        'comment.too_long' => "Le commentaire dépasse la longueur autorisée.",

        // 📄 Formulaires & Validation
        'form.invalid' => "Certains champs sont invalides.",
        'form.missing_fields' => "Veuillez remplir tous les champs obligatoires.",
        'form.submission_success' => "Formulaire soumis avec succès.",
        'form.submission_failed' => "Échec de la soumission du formulaire.",
        'form.csrf_error' => "Erreur de sécurité : jeton CSRF invalide.",
        'form.upload_error' => "Erreur lors du téléchargement du fichier.",
        'form.file_type_invalid' => "Type de fichier non autorisé.",
        'form.file_too_large' => "Le fichier est trop volumineux.",

        // ⚙️ Système & Technique
        'system.error' => "Une erreur technique est survenue. Veuillez réessayer plus tard.",
        'system.db_error' => "Erreur de base de données.",
        'system.not_found' => "La ressource demandée est introuvable.",
        'system.maintenance' => "Le site est en maintenance. Revenez plus tard.",
        'system.timeout' => "La requête a expiré.",
        'system.permission_denied' => "Permission refusée.",
        'system.action_success' => "Action effectuée avec succès.",
        'system.action_failed' => "Échec de l'action demandée.",
    ];

    public static function get(string $key): string {
        return self::$messages[$key] ?? "Message inconnu : $key";
    }
}
