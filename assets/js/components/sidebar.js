import Utils from '../core/utils.js';
import EventManager from '../core/events.js';
import { STORAGE_KEYS } from '../core/constants.js';

class Sidebar {
    constructor() {
        this.sidebar = document.querySelector('.admin-sidebar');
        this.toggleBtn = document.querySelector('[data-sidebar-toggle]');
        this.init();
    }

    init() {
        if (!this.sidebar) return;

        // Restaurer l'état sauvegardé
        this.restoreState();

        // Setup toggle
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggle());
        }

        // Responsive behavior
        this.setupResponsive();
    }

    toggle() {
        if (this.sidebar) {
            const isCollapsed = this.sidebar.classList.toggle('collapsed');
            this.saveState(isCollapsed);
            
            EventManager.emit('sidebar:toggle', { collapsed: isCollapsed });
        }
    }

    saveState(collapsed) {
        Utils.setCookie(STORAGE_KEYS.SIDEBAR_STATE, collapsed ? 'collapsed' : 'expanded');
        localStorage.setItem(STORAGE_KEYS.SIDEBAR_STATE, collapsed ? 'collapsed' : 'expanded');
    }

    restoreState() {
        const savedState = localStorage.getItem(STORAGE_KEYS.SIDEBAR_STATE) || 
                          Utils.getCookie(STORAGE_KEYS.SIDEBAR_STATE);
        
        if (savedState === 'collapsed') {
            this.sidebar.classList.add('collapsed');
        }
    }

    setupResponsive() {
        const handleResize = Utils.debounce(() => {
            if (window.innerWidth <= 1024) {
                this.sidebar.classList.add('mobile-closed');
            } else {
                this.sidebar.classList.remove('mobile-closed');
            }
        }, 250);

        window.addEventListener('resize', handleResize);
        handleResize(); // Initial check
    }
}

export default Sidebar;