<?php
$user_connected = $user_connected ?? false;
$user_role = $user_role ?? null;
?>


<ul class="nav-links">
    <li><a href="/"><i class="fas fa-home"></i> Accueil</a></li>
    <li><a href="/articles"><i class="fas fa-newspaper"></i> Articles</a></li>

    <?php if ($user_connected): ?>
        <?php if ($user_role === 'admin'): ?>
            <li><a href="/admin/dashboard"><i class="fas fa-user-shield"></i> Admin</a></li>
        <?php else: ?>
            <li><a href="/user/profile"><i class="fas fa-user"></i> Mon Compte</a></li>
        <?php endif; ?>
        <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    <?php else: ?>
        <li><a href="/public/login"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
        <li><a href="/public/register"><i class="fas fa-user-plus"></i> Inscription</a></li>
    <?php endif; ?>
</ul>
