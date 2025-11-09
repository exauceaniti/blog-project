<?php
$id = $id ?? 'modal';
$title = $title ?? 'Titre du modal';
$content = $content ?? '';
?>

<div id="<?= $id ?>" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 10% auto; padding: 2rem; width: 50%; border-radius: 8px;">
        <h2><?= htmlspecialchars($title) ?></h2>
        <p><?= htmlspecialchars($content) ?></p>
        <button onclick="document.getElementById('<?= $id ?>').style.display='none'">Fermer</button>
    </div>
</div>
