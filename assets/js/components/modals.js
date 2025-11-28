import Utils from '../core/utils.js';
import EventManager from '../core/events.js';

class ModalSystem {
    constructor() {
        this.modals = new Map();
        this.currentModal = null;
        this.init();
    }

    init() {
        // Détecter tous les modals
        document.querySelectorAll('[data-modal]').forEach(modal => {
            const modalId = modal.id || modal.dataset.modal;
            this.modals.set(modalId, modal);
            this.setupModal(modal);
        });

        // Setup triggers
        document.querySelectorAll('[data-modal-trigger]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const modalId = trigger.dataset.modalTrigger;
                this.open(modalId);
            });
        });

        // Fermer avec ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.currentModal) {
                this.close();
            }
        });
    }

    setupModal(modal) {
        // Close buttons
        modal.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
            closeBtn.addEventListener('click', () => this.close());
        });

        // Close on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.close();
            }
        });
    }

    open(modalId) {
        const modal = this.modals.get(modalId);
        if (!modal) return;

        // Fermer le modal actuel s'il existe
        if (this.currentModal) {
            this.close();
        }

        this.currentModal = modal;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        EventManager.emit('modal:open', { modalId, modal });
    }

    close() {
        if (!this.currentModal) return;

        this.currentModal.classList.remove('active');
        document.body.style.overflow = '';
        
        EventManager.emit('modal:close', { modal: this.currentModal });
        this.currentModal = null;
    }
}

export default new ModalSystem();