<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Brand Section -->
            <div class="footer-section">
                <div class="footer-brand">
                    <a href="/" class="logo">
                        <i class="fas fa-feather-alt logo-icon"></i>
                        <span class="logo-text">Exau-Blog</span>
                    </a>
                    <p class="footer-description">
                        Partager mes passions et découvertes à travers des articles
                        sur le développement web et les technologies modernes.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links avec icônes -->
            <div class="footer-section">
                <h3 class="footer-title">Navigation</h3>
                <div class="footer-links">
                    <!-- Liens de base -->
                    <a href="/" class="footer-link">
                        <i class="fas fa-home footer-icon"></i>
                        <span>Accueil</span>
                    </a>
                    <a href="/articles" class="footer-link">
                        <i class="fas fa-newspaper footer-icon"></i>
                        <span>Articles</span>
                    </a>
                    <a href="/about" class="footer-link">
                        <i class="fas fa-info-circle footer-icon"></i>
                        <span>À propos</span>
                    </a>

                    <?php if ($user_connected ?? false): ?>
                        <!-- Section utilisateur connecté -->
                        <?php if (($user_role ?? null) === 'admin'): ?>
                            <a href="/admin/dashboard" class="footer-link">
                                <i class="fas fa-user-shield footer-icon"></i>
                                <span>Administration</span>
                            </a>
                        <?php else: ?>
                            <a href="/profile" class="footer-link">
                                <i class="fas fa-user footer-icon"></i>
                                <span>Mon Profil</span>
                            </a>
                        <?php endif; ?>
                        <a href="/logout" class="footer-link logout">
                            <i class="fas fa-sign-out-alt footer-icon"></i>
                            <span>Déconnexion</span>
                        </a>
                    <?php else: ?>
                        <!-- Section visiteur -->
                        <a href="/login" class="footer-link">
                            <i class="fas fa-sign-in-alt footer-icon"></i>
                            <span>Connexion</span>
                        </a>
                        <a href="/register" class="footer-link">
                            <i class="fas fa-user-plus footer-icon"></i>
                            <span>Inscription</span>
                        </a>
                    <?php endif; ?>

                    <!-- Contact toujours visible -->
                    <a href="/contact" class="footer-link">
                        <i class="fas fa-envelope footer-icon"></i>
                        <span>Contact</span>
                    </a>
                </div>
            </div>

            <!-- Categories -->
            <div class="footer-section">
                <h3 class="footer-title">Catégories</h3>
                <div class="footer-links">
                    <a href="/category/php" class="footer-link">
                        <i class="fab fa-php footer-icon"></i>
                        <span>PHP</span>
                    </a>
                    <a href="/category/javascript" class="footer-link">
                        <i class="fab fa-js footer-icon"></i>
                        <span>JavaScript</span>
                    </a>
                    <a href="/category/css" class="footer-link">
                        <i class="fab fa-css3-alt footer-icon"></i>
                        <span>CSS</span>
                    </a>
                    <a href="/category/tutorials" class="footer-link">
                        <i class="fas fa-graduation-cap footer-icon"></i>
                        <span>Tutoriels</span>
                    </a>
                </div>
            </div>

            <!-- Contact -->
            <div class="footer-section">
                <h3 class="footer-title">Contact</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <span>contact@exaublog.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone contact-icon"></i>
                        <span>+243 82 085 35 61</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <span>Butembo, RDC</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                <p>&copy; <?= date('Y') ?> Exau-Blog. Tous droits réservés.</p>
            </div>
            <div class="footer-legal">
                <a href="/privacy" class="footer-legal-link">Politique de confidentialité</a>
                <a href="/terms" class="footer-legal-link">Conditions d'utilisation</a>
                <a href="/sitemap" class="footer-legal-link">Plan du site</a>
            </div>
        </div>
    </div>
</footer>

<style>

</style>