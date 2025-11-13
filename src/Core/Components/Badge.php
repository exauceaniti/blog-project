<?php
$label = $label ?? 'Badge';
$color = $color ?? '#0099ffff';
?>

<span class="badge" style="background-color: <?= $color ?>; color: white; padding: 0.3rem 0.6rem; border-radius: 12px; font-size: 0.8rem;">
    <?= htmlspecialchars($label) ?>
</span>
