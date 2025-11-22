<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Brand Section -->
            <div class="footer-section">
                <div class="footer-brand">
                    <div class="logo">
                        <i class="fas fa-feather-alt"></i>
                        MonBlog
                    </div>
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
                <h3>Navigation</h3>

                <?php
                $user_connected = $user_connected ?? false;
                $user_role = $user_role ?? null;
                ?>

                <div class="footer-links">
                    <!-- Liens de base -->
                    <a href="/" class="footer-link">
                        <i class="fas fa-home footer-icon"></i>
                        Accueil
                    </a>
                    <a href="/articles" class="footer-link">
                        <i class="fas fa-newspaper footer-icon"></i>
                        Articles
                    </a>
                    <a href="/about" class="footer-link">
                        <i class="fas fa-info-circle footer-icon"></i>
                        À propos
                    </a>

                    <?php if ($user_connected): ?>
                        <!-- Section utilisateur connecté -->
                        <?php if ($user_role === 'admin'): ?>
                            <a href="/admin/dashboard" class="footer-link">
                                <i class="fas fa-user-shield footer-icon"></i>
                                Administration
                            </a>
                        <?php else: ?>
                            <a href="/profile" class="footer-link">
                                <i class="fas fa-user footer-icon"></i>
                                Mon Profil
                            </a>
                        <?php endif; ?>
                        <a href="/logout" class="footer-link">
                            <i class="fas fa-sign-out-alt footer-icon"></i>
                            Déconnexion
                        </a>
                    <?php else: ?>
                        <!-- Section visiteur -->
                        <a href="/login" class="footer-link">
                            <i class="fas fa-sign-in-alt footer-icon"></i>
                            Connexion
                        </a>
                        <a href="/register" class="footer-link">
                            <i class="fas fa-user-plus footer-icon"></i>
                            Inscription
                        </a>
                    <?php endif; ?>

                    <!-- Contact toujours visible -->
                    <a href="/contact" class="footer-link">
                        <i class="fas fa-envelope footer-icon"></i>
                        Contact
                    </a>
                </div>
            </div>

            <!-- Categories -->
            <div class="footer-section">
                <h3>Catégories</h3>
                <div class="footer-links">
                    <a href="/category/php" class="footer-link">PHP</a>
                    <a href="/category/javascript" class="footer-link">JavaScript</a>
                    <a href="/category/css" class="footer-link">CSS</a>
                    <a href="/category/tutorials" class="footer-link">Tutoriels</a>
                </div>
            </div>

            <!-- Contact -->
            <div class="footer-section">
                <h3>Contact</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <span>contact@monblog.com</span>
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
                <p>&copy; <?= date('Y') ?> MonBlog. Tous droits réservés.</p>
            </div>
            <div class="footer-legal">
                <a href="/privacy" class="footer-link">Politique de confidentialité</a>
                <a href="/terms" class="footer-link">Conditions d'utilisation</a>
                <a href="/sitemap" class="footer-link">Plan du site</a>
            </div>
        </div>
    </div>
</footer>