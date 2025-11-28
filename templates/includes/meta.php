<!-- templates/includes/meta.php -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- CSS Principal -->
<link rel="stylesheet" href="/public/assets/css/main.css">
<script type="module" src="/public/assets/js/main.js"></script>
<script src="/public/assets/js/layout/header.js"></script>
<script src="/public/assets/js/layout/sidebar.js"></script>
<script src="/public/assets/js/pages/auth_ui.js"></script>




<!-- CSS additionnel si besoin -->
<?php if (isset($additional_css)): ?>
    <?php foreach ((array)$additional_css as $css_file): ?>
        <link rel="stylesheet" href="/assets/css/<?= $css_file ?>">
    <?php endforeach; ?>
<?php endif; ?>

<!-- JavaScript Modulaire (ES6 modules) -->
<script type="module" src="/assets/js/main.js"></script>

<!-- Fallback pour les vieux navigateurs -->
<script nomodule>
    console.warn('Votre navigateur ne supporte pas les modules ES6. Veuillez le mettre à jour.');
    // Charger une version bundle si nécessaire
    // <script src="/assets/js/bundle.js">
</script>
</script>