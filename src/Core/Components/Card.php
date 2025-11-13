<?php
use Core\Session\UserContext;

$title = $title ?? 'Profil de ' . UserContext::name();
$content = $content ?? 'Bienvenue dans ton espace personnel';
// $image = $image ?? UserContext::avatar();
?>

<div class="card" style="border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; background-color: white;">
    <?php if ($image): ?>
        <img src="<?= $image ?>" alt="Avatar" style="width: 100%; border-radius: 8px;">
    <?php endif; ?>
    <h3 style="margin-top: 1rem;"><?= htmlspecialchars($title) ?></h3>
    <p><?= htmlspecialchars($content) ?></p>
</div>
