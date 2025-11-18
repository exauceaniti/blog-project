<?php
$user_connected = $user_connected ?? false;
$user_role = $user_role ?? null;
?>

<nav class="site-nav">
    <ul class="nav-links">
        <li><a href="/"><i class="fas fa-home"></i> Accueil</a></li>
        <li><a href="/articles"><i class="fas fa-newspaper"></i> Articles</a></li>

        <?php if ($user_connected): ?>
            <?php if ($user_role === 'admin'): ?>
                <li><a href="/admin/dashboard"><i class="fas fa-user-shield"></i> dashboard</a></li>
            <?php else: ?>
                <li><a href="/profile"><i class="fas fa-user"></i> Profile</a></li>
            <?php endif; ?>
            <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        <?php else: ?>
            <li><a href="/login"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
            <li><a href="/register"><i class="fas fa-user-plus"></i> Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>

<style>
.site-nav {
    background: #5cc4beff;
    padding: 12px 20px;
}
.nav-links {
    list-style: none;
    display: flex;
    justify-content: center;
    gap: 25px;
    margin: 0;
    padding: 0;
}
.nav-links a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-family: 'Roboto', sans-serif;
    transition: color 0.3s;
}
.nav-links a:hover {
    color: #00ffddff;
}
.nav-links i {
    margin-right: 6px;
}
</style>
