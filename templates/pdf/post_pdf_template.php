<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Configuration de la page */
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', Arial, sans-serif; color: #333; line-height: 1.5; background-color: #fff; margin: 0; padding: 0; }
        
        /* En-tête stylisé */
        .header { text-align: center; padding: 20px; background-color: #f8f9fa; border-bottom: 4px solid #e74c3c; margin-bottom: 40px; }
        .header h1 { margin: 0; color: #2c3e50; font-size: 28px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #7f8c8d; font-size: 14px; }

        /* Système de Cartes */
        .article-card { 
            border: 1px solid #e1e4e8; 
            border-radius: 12px; 
            padding: 25px; 
            margin-bottom: 40px; 
            page-break-inside: avoid; /* Évite de couper une carte en deux */
            background-color: #ffffff;
        }

        .title { color: #2c3e50; font-size: 22px; margin-top: 0; margin-bottom: 8px; border-left: 5px solid #e74c3c; padding-left: 15px; }
        
        .meta { font-size: 11px; color: #95a5a6; margin-bottom: 20px; font-style: italic; display: block; }

        /* Image centrée et propre */
        .image-box { text-align: center; margin: 20px 0; background-color: #fdfdfd; padding: 10px; border-radius: 8px; }
        .image-box img { max-width: 100%; height: auto; border-radius: 6px; border: 1px solid #eee; }

        .content { text-align: justify; font-size: 13px; color: #444; line-height: 1.7; }

        /* Pied de page */
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; height: 30px; text-align: center; font-size: 10px; color: #bdc3c7; border-top: 1px solid #eee; padding-top: 10px; }
        
        /* Décoration */
        .badge { display: inline-block; background: #e74c3c; color: white; padding: 3px 10px; border-radius: 20px; font-size: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>JOURNAL DU BLOG</h1>
        <p>Archive officielle des articles récents • <?= date('d/m/Y') ?></p>
    </div>

    <div class="container">
        <?php foreach ($posts as $post): ?>
            <div class="article-card">
                <span class="badge">Article publié</span>
                <h2 class="title"><?= htmlspecialchars($post->titre) ?></h2>
                <span class="meta">Publié le <?= $post->created_at ?> par l'équipe rédactionnelle</span>
                
                <?php if (!empty($post->image_base64)): ?>
                    <div class="image-box">
                        <img src="<?= $post->image_base64 ?>" style="width: 450px;">
                    </div>
                <?php else: ?>
                    <div style="text-align:center; padding: 20px; color: #ccc; border: 1px dashed #eee; margin: 15px 0;">
                        📷 Image non illustrée
                    </div>
                <?php endif; ?>

                <div class="content">
                    <?= nl2br(htmlspecialchars($post->contenu)) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        © <?= date('Y') ?> Mon Blog Project - Tous droits réservés - Page {PAGENO}
    </div>

</body>
</html>