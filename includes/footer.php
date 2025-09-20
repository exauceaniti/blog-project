<?php
// includes/footer.php
?>
</main>

<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-about">
                <div class="footer-logo">Mon<span>Blog</span></div>
                <p class="footer-about">Une plateforme d'information moderne qui vous permet de rester à jour sur les dernières actualités et tendances.</p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-links">
                <h3 class="footer-title">Liens rapides</h3>
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Articles</a></li>
                    <li><a href="#">Catégories</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h3 class="footer-title">Catégories</h3>
                <ul>
                    <li><a href="#">Technologie</a></li>
                    <li><a href="#">Science</a></li>
                    <li><a href="#">Culture</a></li>
                    <li><a href="#">Santé</a></li>
                    <li><a href="#">Environnement</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h3 class="footer-title">Contact</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Butembo, DR Congo</li>
                    <li><i class="fas fa-phone"></i> +243 820 853 162</li>
                    <li><i class="fas fa-envelope"></i> exauaceaniti@gmail.com</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2023 OceanBlog. Tous droits réservés. Conçu avec passion par Exaucé.</p>
        </div>
    </div>
</footer>

<style>
    /* Corps de la page en mode flex */
    html,
    body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1;
        /* Prend tout l’espace dispo pour pousser le footer en bas */
    }

    footer {
        background: #0a192f;
        color: #ccd6f6;
        padding: 20px 0;
        margin-top: auto;
        /* Assure que le footer colle au bas */
    }

    .footer-container {
        width: 90%;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer-container p {
        font-size: 0.9rem;
    }

    .footer-links {
        list-style: none;
        display: flex;
        gap: 15px;
    }

    .footer-links a {
        color: #64ffda;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer-links a:hover {
        color: white;
    }
</style>
</body>

</html>