<?php
// Si tu utilises un layout, assure-toi d'inclure les fichiers d'en-tête et de mise en page ici
// include_once 'templates/layout/public.php'; 
?>

<div class="form-container">
    <h2>Créer un compte</h2>

    <?php if (isset($flash['success'])): ?>
        <p class="flash-success"><?= $flash['success'] ?></p>
    <?php endif; ?>

    <form method="POST" action="/register"> <?php if (isset($errors['global'])): ?>
            <p class="error-message-global"><?= $errors['global'] ?></p>
        <?php endif; ?>

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text"
                id="nom"
                name="nom"
                value="<?= htmlspecialchars($old['nom'] ?? '') ?>"
                required>
            <?php if (isset($errors['nom'])): ?>
                <span class="error-message-field"><?= $errors['nom'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                required>
            <?php if (isset($errors['email'])): ?>
                <span class="error-message-field"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password"
                id="password"
                name="password"
                value="" required>
            <?php if (isset($errors['password'])): ?>
                <span class="error-message-field"><?= $errors['password'] ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="/login">Connectez-vous</a></p>
</div>