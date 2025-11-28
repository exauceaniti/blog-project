// Point d'entrée principal de l'application
import EventManager from './core/events.js';
import Header from './components/header.js';
import ModalSystem from './components/modals.js';
import Sidebar from './layout/sidebar.js';

class App {
    constructor() {
        this.components = new Map();
        this.init();
    }

    init() {
        // Initialiser les composants globaux
        this.initializeComponents();
        
        // Setup global events
        this.setupGlobalEvents();
        
        // App ready
        this.onReady();
    }

    initializeComponents() {
        // Header et navigation
        if (document.querySelector('.site-header')) {
            this.components.set('header', new Header());
        }

        // Modal system
        this.components.set('modals', ModalSystem);

        // Sidebar admin
        if (document.querySelector('.admin-sidebar')) {
            this.components.set('sidebar', new Sidebar());
        }

        // Charger les composants spécifiques aux pages
        this.initializePageSpecificComponents();
    }

    initializePageSpecificComponents() {
        const bodyClass = document.body.className;
        
        // Page admin
        if (bodyClass.includes('admin-page') || window.location.pathname.includes('/admin')) {
            import('./pages/admin.js')
                .then(module => {
                    this.components.set('admin', new module.default());
                })
                .catch(err => console.log('Admin page components not needed'));
        }

        // Page blog
        if (bodyClass.includes('blog-page') || window.location.pathname.includes('/articles')) {
            import('./pages/blog.js')
                .then(module => {
                    this.components.set('blog', new module.default());
                })
                .catch(err => console.log('Blog page components not needed'));
        }
    }

    setupGlobalEvents() {
        // Gestion du loading global
        EventManager.on('app:loading', (e) => {
            this.toggleGlobalLoading(e.detail.loading);
        });

        // Gestion des erreurs globales
        window.addEventListener('error', (e) => {
            console.error('Global error:', e.error);
            EventManager.emit('app:error', { error: e.error });
        });
    }

    toggleGlobalLoading(loading) {
        if (loading) {
            document.body.classList.add('loading');
        } else {
            document.body.classList.remove('loading');
        }
    }

    onReady() {
        document.addEventListener('DOMContentLoaded', () => {
            console.log('🚀 App initialized successfully');
            EventManager.emit('app:ready');
        });
    }

    // Méthode pour accéder aux composants
    getComponent(name) {
        return this.components.get(name);
    }
}

// Démarrer l'application
const app = new App();

// Exposer l'app globalement pour le debug
window.App = app;

export default app;