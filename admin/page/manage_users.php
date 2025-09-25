<?php
// admin/pages/manage_users.php
session_start();

require_once "../config/connexion.php";
require_once "../models/User.php";

$connexion = new Connexion();
$userManager = new User($connexion);

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'ajouter':
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if ($email && $password) {
                // Utiliser ta méthode sInscrire ou créer une méthode spécifique admin
                $userManager->sInscrire($email, $password);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Utilisateur ajouté avec succès!'];
                header("Location: ?page=manage_users");
                exit;
            }
            break;

        case 'supprimer':
            $id = (int)$_POST['id'];
            if ($id) {
                // Décommenter et adapter si tu as une méthode supprimerUtilisateur
                // $userManager->supprimerUtilisateur($id);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Utilisateur supprimé avec succès!'];
                header("Location: ?page=manage_users");
                exit;
            }
            break;

        case 'changer_role':
            $id = (int)$_POST['id'];
            $role = trim($_POST['role']);

            if ($id && $role) {
                // Méthode à implémenter dans ta classe User
                // $userManager->changerRole($id, $role);
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Rôle utilisateur modifié!'];
                header("Location: ?page=manage_users");
                exit;
            }
            break;
    }
}

// Récupérer les utilisateurs (décommenter quand ta méthode sera prête)
// $users = $userManager->voirUtilisateurs();
$users = []; // Temporairement vide
?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-user-plus"></i> Ajouter un Utilisateur
    </h2>

    <form method="POST">
        <input type="hidden" name="action" value="ajouter">

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Adresse email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter l'Utilisateur
        </button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-user-cog"></i> Gestion des Rôles
    </h2>

    <form method="POST">
        <input type="hidden" name="action" value="changer_role">

        <div class="form-group">
            <label for="role_id">ID de l'utilisateur</label>
            <input type="number" id="role_id" name="id" placeholder="ID utilisateur" required>
        </div>

        <div class="form-group">
            <label for="role">Nouveau rôle</label>
            <select id="role" name="role" required>
                <option value="">Sélectionner un rôle</option>
                <option value="user">Utilisateur</option>
                <option value="admin">Administrateur</option>
                <option value="moderator">Modérateur</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">
            <i class="fas fa-sync-alt"></i> Changer le Rôle
        </button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-user-times"></i> Supprimer un Utilisateur
    </h2>

    <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
        <input type="hidden" name="action" value="supprimer">

        <div class="form-group">
            <label for="supprimer_id">ID de l'utilisateur à supprimer</label>
            <input type="number" id="supprimer_id" name="id" placeholder="ID utilisateur" required>
        </div>

        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash"></i> Supprimer l'Utilisateur
        </button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <i class="fas fa-users"></i> Liste des Utilisateurs (<?= count($users) ?>)
    </h2>

    <?php if (empty($users)): ?>
        <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
            <i class="fas fa-users fa-3x" style="margin-bottom: 1rem;"></i>
            <p>Aucun utilisateur trouvé.</p>
            <p style="font-size: 0.9rem;">La fonctionnalité d'affichage des utilisateurs sera bientôt disponible.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 1rem;">
            <?php foreach ($users as $user): ?>
                <div style="padding: 1rem; border: 1px solid var(--border-color); border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="color: var(--primary-color);"><?= htmlspecialchars($user['email']) ?></strong>
                        <div style="color: var(--text-secondary); font-size: 0.9rem;">
                            ID: <?= $user['id'] ?> •
                            Rôle: <span style="background: var(--accent-1); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                                <?= htmlspecialchars($user['role'] ?? 'user') ?>
                            </span> •
                            Inscrit le: <?= date('d/m/Y', strtotime($user['date_inscription'])) ?>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" class="btn btn-warning" onclick="fillUserForm(<?= $user['id'] ?>, '<?= $user['role'] ?? 'user' ?>')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function fillUserForm(userId, userRole) {
        document.getElementById('role_id').value = userId;
        document.getElementById('supprimer_id').value = userId;
        document.getElementById('role').value = userRole;
    }
</script>