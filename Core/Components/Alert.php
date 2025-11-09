<?php
$type = $type ?? 'info';
$message = $message ?? '';

$colors = [
    'success' => '#00f767ff',
    'error'   => '#ff1900ff',
    'info'    => '#0099ffff',
    'warning' => '#ff9d00ff'
];

$bgColor = $colors[$type] ?? $colors['info'];
?>

<div class="alert" style="background-color: <?= $bgColor ?>; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
    <?= htmlspecialchars($message) ?>
</div>
