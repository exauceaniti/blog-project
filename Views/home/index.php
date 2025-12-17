<?php

/**
 * views/home/index.php
 * Page d'accueil principale - Version Élégante & Professionnelle
 * Reçoit $latest_articles_list du PostController::home().
 */

// Assurez-vous que $latest_articles_list est défini
$articles_list = $latest_articles_list ?? [];
?>

<!-- Section Hero - Version Élégante -->
<section class="hero-section bg-gradient-to-br from-primary-50 via-white to-secondary-50">
    <div class="container mx-auto px-4 py-16 md:py-24">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Badge d'élite -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-primary-100 shadow-xs mb-8">
                <span class="text-xs font-semibold text-primary-700 uppercase tracking-wider">💎 Plateforme Premium</span>
            </div>

            <!-- Titre principal -->
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-dark mb-6 leading-tight">
                Exau<span class="text-primary">Blog</span>
            </h1>

            <p class="text-lg md:text-xl text-secondary-700 mb-8 max-w-2xl mx-auto leading-relaxed">
                Votre source d'expertise en développement web et technologies émergentes.
            </p>

            <!-- Actions principales -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
                <a href="/articles" class="c-btn c-btn--lg group">
                    <span>Explorer les articles</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>

                <a href="#latest-articles" class="c-btn c-btn--outline c-btn--lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    Découvrir
                </a>
            </div>

            <!-- Indicateurs de confiance -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-8 border-t border-secondary-100">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary mb-1">Expert</div>
                    <div class="text-sm text-secondary-600">Contenu</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary mb-1">Actualisé</div>
                    <div class="text-sm text-secondary-600">Quotidiennement</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary mb-1">Pratique</div>
                    <div class="text-sm text-secondary-600">Tutoriels</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary mb-1">Communauté</div>
                    <div class="text-sm text-secondary-600">Active</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Articles Récents -->
<section id="latest-articles" class="latest-articles-section py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4">
        <!-- En-tête de section -->
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-4xl font-bold text-dark mb-4">
                Articles <span class="text-primary">Récents</span>
            </h2>
            <p class="text-lg text-secondary-700 max-w-2xl mx-auto">
                Découvrez nos dernières publications sur le développement web et les nouvelles technologies.
            </p>
        </div>

        <?php if (!empty($articles_list)): ?>
            <!-- Grille d'articles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <?php foreach ($articles_list as $index => $article): ?>
                    <?php
                    $card_params = [
                        'article' => $article,
                        'variant' => 'home'
                    ];
                    \App\Core\Render\Fragment::articleCard($card_params);
                    ?>
                <?php endforeach; ?>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                <a href="/articles" class="c-btn c-btn--lg inline-flex items-center">
                    <span>Voir tous les articles</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        <?php else: ?>
            <!-- État vide -->
            <div class="text-center py-16 bg-secondary-50 rounded-lg border border-dashed border-secondary-200">
                <svg class="w-16 h-16 mx-auto text-secondary-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-xl font-semibold text-secondary-700 mb-2">Aucun article disponible</h3>
                <p class="text-secondary-600 max-w-md mx-auto">
                    Notre équipe prépare du contenu de qualité. Revenez bientôt !
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Section Catégories -->
<section class="categories-section py-16 bg-secondary-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-4xl font-bold text-dark mb-4">
                Thèmes <span class="text-primary">Principaux</span>
            </h2>
            <p class="text-lg text-secondary-700 max-w-2xl mx-auto">
                Explorez nos catégories de contenu spécialisées
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="/articles?category=web" class="c-card text-center group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-lg bg-primary-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <h3 class="font-semibold text-dark mb-2">Développement Web</h3>
                <p class="text-sm text-secondary-600">Frontend, Backend, Frameworks</p>
            </a>

            <a href="/articles?category=tech" class="c-card text-center group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-lg bg-primary-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-dark mb-2">Technologies</h3>
                <p class="text-sm text-secondary-600">Outils, Langages, Tendances</p>
            </a>

            <a href="/articles?category=best-practices" class="c-card text-center group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-lg bg-primary-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-dark mb-2">Bonnes Pratiques</h3>
                <p class="text-sm text-secondary-600">Code propre, Architecture, Tests</p>
            </a>

            <a href="/articles?category=career" class="c-card text-center group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-lg bg-primary-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-dark mb-2">Carrière</h3>
                <p class="text-sm text-secondary-600">Évolution, Compétences, Marché</p>
            </a>
        </div>
    </div>
</section>

<!-- Section Mission -->
<section class="mission-section py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl md:text-4xl font-bold text-dark mb-8">
                Notre <span class="text-primary">Mission</span>
            </h2>

            <div class="space-y-8">
                <div class="text-left">
                    <h3 class="text-xl font-semibold text-dark mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        Partager le Savoir
                    </h3>
                    <p class="text-secondary-700">
                        Nous croyons en la puissance de l'éducation et du partage des connaissances.
                        Notre objectif est de rendre les concepts techniques accessibles à tous.
                    </p>
                </div>

                <div class="text-left">
                    <h3 class="text-xl font-semibold text-dark mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        Inspirer la Communauté
                    </h3>
                    <p class="text-secondary-700">
                        À travers nos articles et tutoriels, nous souhaitons inspirer la prochaine
                        génération de développeurs et d'innovateurs technologiques.
                    </p>
                </div>

                <div class="text-left">
                    <h3 class="text-xl font-semibold text-dark mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        Rester à la Pointe
                    </h3>
                    <p class="text-secondary-700">
                        Nous nous engageons à couvrir les dernières tendances et technologies
                        pour vous maintenir informé dans un secteur en constante évolution.
                    </p>
                </div>
            </div>

            <!-- Citation -->
            <div class="mt-12 pt-8 border-t border-secondary-100">
                <blockquote class="text-lg text-secondary-700 italic">
                    "La meilleure façon de prédire l'avenir est de le créer."
                </blockquote>
                <cite class="text-sm text-secondary-600 mt-2 block">— Peter Drucker</cite>
            </div>
        </div>
    </div>
</section>

<!-- Section Newsletter -->
<section class="newsletter-section py-16 bg-primary-50">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <div class="c-card">
                <h3 class="text-2xl font-bold text-dark mb-4">
                    Restez informé
                </h3>

                <p class="text-secondary-700 mb-8">
                    Recevez nos derniers articles et ressources directement dans votre boîte mail.
                </p>

                <form class="newsletter-form">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-grow">
                            <input type="email"
                                placeholder="votre@email.com"
                                class="w-full px-4 py-3 rounded-lg border border-secondary-200 focus:border-primary focus:ring-2 focus:ring-primary-100 outline-none transition-all"
                                required>
                        </div>
                        <button type="submit" class="c-btn whitespace-nowrap">
                            S'abonner
                        </button>
                    </div>
                    <p class="text-xs text-secondary-600 text-center mt-4">
                        Nous respectons votre vie privée. Désabonnez-vous à tout moment.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Styles supplémentaires -->
<style>
    .hero-section {
        background-attachment: fixed;
    }

    .c-card.group:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .c-card.group:hover .text-primary {
        color: var(--color-primary-700);
    }

    .newsletter-form input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    @media (max-width: 640px) {
        .hero-section h1 {
            font-size: 2.5rem;
        }
    }
</style>