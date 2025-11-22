<!-- templates/includes/meta.php -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- CSS Principal -->
<link rel="stylesheet" href="/assets/css/main.css">

<!-- CSS additionnel si besoin -->
<?php if (isset($additional_css)): ?>
    <?php foreach ((array)$additional_css as $css_file): ?>
        <link rel="stylesheet" href="/assets/css/<?= $css_file ?>">
    <?php endforeach; ?>
<?php endif; ?>