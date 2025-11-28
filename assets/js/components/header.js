import Utils from '../core/utils.js';
import EventManager from '../core/events.js';

class Header {
    constructor() {
        this.header = document.querySelector('.site-header');
        this.mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        this.navMain = document.querySelector('.nav-main');
        this.userDropdowns = document.querySelectorAll('.user-dropdown');
        
        this.init();
    }

    init() {
        if (this.mobileMenuBtn && this.navMain) {
            this.setupMobileMenu();
        }

        if (this.userDropdowns.length > 0) {
            this.setupDropdowns();
        }

        this.setupScrollEffect();
        this.setupClickOutside();
    }

    setupMobileMenu() {
        this.mobileMenuBtn.addEventListener('click', () => {
            Utils.toggleClass(this.mobileMenuBtn, 'active');
            Utils.toggleClass(this.navMain, 'active');
            
            // Empêcher le défilement
            document.body.style.overflow = this.navMain.classList.contains('active') ? 'hidden' : '';
            
            EventManager.emit('header:mobile:toggle', {
                open: this.navMain.classList.contains('active')
            });
        });
    }

    setupDropdowns() {
        this.userDropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('.user-trigger');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (trigger && menu) {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Fermer les autres dropdowns
                    this.closeAllDropdownsExcept(menu);
                    
                    // Toggle current dropdown
                    Utils.toggleClass(menu, 'active');
                    Utils.toggleClass(trigger, 'active');
                });
            }
        });
    }

    closeAllDropdownsExcept(exceptMenu = null) {
        this.userDropdowns.forEach(dropdown => {
            const menu = dropdown.querySelector('.dropdown-menu');
            const trigger = dropdown.querySelector('.user-trigger');
            
            if (menu !== exceptMenu) {
                menu.classList.remove('active');
                trigger.classList.remove('active');
            }
        });
    }

    setupScrollEffect() {
        let lastScrollY = window.scrollY;
        
        const handleScroll = Utils.throttle(() => {
            if (window.scrollY > 100) {
                this.header.classList.add('scrolled');
            } else {
                this.header.classList.remove('scrolled');
            }

            // Hide header on scroll down
            if (window.scrollY > lastScrollY && window.scrollY > 200) {
                this.header.classList.add('hidden');
            } else {
                this.header.classList.remove('hidden');
            }

            lastScrollY = window.scrollY;
        }, 100);

        window.addEventListener('scroll', handleScroll);
    }

    setupClickOutside() {
        document.addEventListener('click', (e) => {
            // Fermer le menu mobile
            if (this.navMain && this.navMain.classList.contains('active') && 
                !e.target.closest('.nav-main') && !e.target.closest('.mobile-menu-btn')) {
                this.mobileMenuBtn.classList.remove('active');
                this.navMain.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Fermer les dropdowns
            if (!e.target.closest('.user-dropdown')) {
                this.closeAllDropdownsExcept();
            }
        });
    }
}

export default Header;