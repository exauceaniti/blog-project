<?php
$text = $text ?? 'Bouton';
$type = $type ?? 'button';
$onclick = $onclick ?? '';
?>

<button type="<?= $type ?>" onclick="<?= $onclick ?>" style="background-color: #54baffff; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 4px; cursor: pointer;">
    <?= htmlspecialchars($text) ?>
</button>
