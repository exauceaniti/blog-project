// public/assets/js/auth-ui.js
    class AuthUI {
        constructor(form) {
            this.form = form;
            this.init();
        }

        init() {
            this.setupFloatingLabels();
            this.setupPasswordToggles();
            this.setupPasswordStrength();
            this.setupVisualValidation();
        }

        setupFloatingLabels() {
            const inputs = this.form.querySelectorAll('.floating-input');
        
            inputs.forEach(input => {
                // Initialiser l'état du label
                if (input.value) {
                    input.classList.add('has-value');
                }

                input.addEventListener('input', () => {
                    if (input.value) {
                        input.classList.add('has-value');
                    } else {
                        input.classList.remove('has-value');
                    }
                });

                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', () => {
                    input.parentElement.classList.remove('focused');
                    this.updateFieldVisualState(input);
                });
            });
        }

        setupPasswordToggles() {
            const toggles = this.form.querySelectorAll('.password-toggle');
        
            toggles.forEach(toggle => {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    const input = toggle.closest('.floating-group').querySelector('.floating-input');
                    const icon = toggle.querySelector('i');
                
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'fa-solid fa-eye-slash';
                        toggle.setAttribute('aria-label', 'Masquer le mot de passe');
                    } else {
                        input.type = 'password';
                        icon.className = 'fa-solid fa-eye';
                        toggle.setAttribute('aria-label', 'Afficher le mot de passe');
                    }
                
                    // Remettre le focus sur l'input
                    input.focus();
                });
            });
        }

        setupPasswordStrength() {
            const passwordInput = this.form.querySelector('#register-password');
            if (!passwordInput) return;

            const strengthBar = this.form.querySelector('.strength-fill');
            const strengthText = this.form.querySelector('.strength-text');

            passwordInput.addEventListener('input', () => {
                const password = passwordInput.value;
                const strength = this.calculatePasswordStrength(password);
            
                if (strengthBar) strengthBar.setAttribute('data-strength', strength);
                if (strengthText) strengthText.textContent = this.getStrengthText(strength);
            
                this.updatePasswordConfirmVisual();
            });
        }

        calculatePasswordStrength(password) {
            let strength = 0;
        
            if (password.length >= 6) strength += 1;
            if (password.length >= 8) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
        
            return Math.min(strength, 4);
        }

        getStrengthText(strength) {
            const texts = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
            return texts[strength] || '';
        }

        setupVisualValidation() {
            const inputs = this.form.querySelectorAll('.floating-input');
        
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    this.clearVisualError(input);
                });

                // Validation visuelle de l'email
                if (input.type === 'email') {
                    input.addEventListener('blur', () => {
                        this.validateEmailVisual(input);
                    });
                }

                // Validation visuelle de la confirmation de mot de passe
                if (input.id === 'register-password-confirm') {
                    input.addEventListener('input', () => {
                        this.updatePasswordConfirmVisual();
                    });
                }
            });
        }

        validateEmailVisual(field) {
            const value = field.value.trim();
            if (!value) return;
        
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.showVisualError(field, 'Format d\'email invalide');
            } else {
                this.clearVisualError(field);
            }
        }

        updatePasswordConfirmVisual() {
            const password = this.form.querySelector('#register-password');
            const confirm = this.form.querySelector('#register-password-confirm');
        
            if (!password || !confirm || !confirm.value) return;

            if (password.value !== confirm.value) {
                this.showVisualError(confirm, 'Les mots de passe ne correspondent pas');
            } else {
                this.clearVisualError(confirm);
            }
        }

        updateFieldVisualState(field) {
            // Ajouter une classe pour les champs valides (optionnel)
            if (field.value && !field.classList.contains('error')) {
                field.classList.add('valid');
            } else {
                field.classList.remove('valid');
            }
        }

        showVisualError(field, message) {
            field.classList.add('error');
            this.clearVisualError(field);
        
            const errorElement = document.createElement('span');
            errorElement.className = 'field-error';
            errorElement.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i>${message}`;
            field.parentNode.appendChild(errorElement);
        }

        clearVisualError(field) {
            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
        }
    }

    // Gestion des animations et interactions globales
    class AuthPageAnimations {
        constructor() {
            this.init();
        }

        init() {
            this.setupPageAnimations();
            this.setupAutoDismissAlerts();
            this.setupSmoothTransitions();
        }

        setupPageAnimations() {
            // Animation d'entrée de la carte
            const authCard = document.querySelector('.auth-card');
            if (authCard) {
                authCard.style.opacity = '0';
                authCard.style.transform = 'translateY(20px)';
            
                setTimeout(() => {
                    authCard.style.transition = 'all 0.6s ease';
                    authCard.style.opacity = '1';
                    authCard.style.transform = 'translateY(0)';
                }, 100);
            }

            // Animation des éléments de formulaire
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.opacity = '0';
                group.style.transform = 'translateX(-20px)';
            
                setTimeout(() => {
                    group.style.transition = 'all 0.4s ease';
                    group.style.opacity = '1';
                    group.style.transform = 'translateX(0)';
                }, 200 + (index * 100));
            });
        }

        setupAutoDismissAlerts() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'all 0.3s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 300);
                }, 5000);
            });
        }

        setupSmoothTransitions() {
            // Transition douce entre les pages d'auth
            const authLinks = document.querySelectorAll('.auth-link');
            authLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const href = link.getAttribute('href');
                
                    // Animation de sortie
                    const authCard = document.querySelector('.auth-card');
                    if (authCard) {
                        authCard.style.transition = 'all 0.4s ease';
                        authCard.style.opacity = '0';
                        authCard.style.transform = 'translateY(-20px)';
                    }
                
                    // Navigation après animation
                    setTimeout(() => {
                        window.location.href = href;
                    }, 400);
                });
            });
        }
    }

    // Initialisation globale
    document.addEventListener('DOMContentLoaded', () => {
        // Initialiser les formulaires d'authentification
        const authForms = document.querySelectorAll('.auth-form');
        authForms.forEach(form => {
            new AuthUI(form);
        });

        // Initialiser les animations de page
        new AuthPageAnimations();

        // Auto-focus sur le premier champ avec animation
        const firstInput = document.querySelector('.floating-input');
        if (firstInput) {
            setTimeout(() => {
                firstInput.focus();
                firstInput.parentElement.classList.add('focused');
            }, 600);
        }

        // Effet de pulsation sur le bouton submit
        const submitButton = document.querySelector('.btn-primary');
        if (submitButton) {
            submitButton.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-2px)';
            });
        
            submitButton.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        }

        console.log('🎨 Interface d\'authentification initialisée');
    });

    // Fonctions utilitaires globales
    window.authUtils = {
        // Basculer la visibilité du mot de passe
        togglePassword: function (inputId) {
            const input = document.getElementById(inputId);
            const toggle = document.querySelector(`[aria-label*="${inputId}"]`);
        
            if (input && toggle) {
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
                    toggle.setAttribute('aria-label', 'Masquer le mot de passe');
                } else {
                    input.type = 'password';
                    toggle.innerHTML = '<i class="fa-solid fa-eye"></i>';
                    toggle.setAttribute('aria-label', 'Afficher le mot de passe');
                }
            }
        },
    
        // Vérifier la force du mot de passe
        checkPasswordStrength: function (password) {
            let strength = 0;
            if (password.length >= 6) strength += 1;
            if (password.length >= 8) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
            return Math.min(strength, 4);
        },
    
        // Animer un élément
        animateElement: function (element, animation) {
            element.style.animation = `${animation} 0.5s ease`;
            setTimeout(() => {
                element.style.animation = '';
            }, 500);
        }
    };
