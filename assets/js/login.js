// Toggle du thème sombre
const themeToggle = document.getElementById('themeToggle');
const currentTheme = localStorage.getItem('theme') || 'light';

// Appliquer le thème sauvegardé
if (currentTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
    themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
}

themeToggle.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    
    if (currentTheme === 'dark') {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
        themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
    } else {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    }
});

// Toggle pour afficher/masquer le mot de passe
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
}

// Charger l'email sauvegardé si "Se souvenir de moi" était coché
document.addEventListener('DOMContentLoaded', function() {
    const rememberCheckbox = document.getElementById('remember');
    const emailInput = document.getElementById('email');
    
    // Vérifier si un email est sauvegardé dans les cookies
    const savedEmail = getCookie('remember_email');
    if (savedEmail) {
        emailInput.value = savedEmail;
        if (rememberCheckbox) {
            rememberCheckbox.checked = true;
        }
    }
});

// Fonction pour récupérer un cookie
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// Sauvegarder l'email si "Se souvenir de moi" est coché
const rememberCheckbox = document.getElementById('remember');
const emailInput = document.getElementById('email');

if (rememberCheckbox && emailInput) {
    rememberCheckbox.addEventListener('change', function() {
        if (this.checked && emailInput.value) {
            // Sauvegarder l'email pour 30 jours
            const expiryDate = new Date();
            expiryDate.setDate(expiryDate.getDate() + 30);
            document.cookie = `remember_email=${encodeURIComponent(emailInput.value)}; expires=${expiryDate.toUTCString()}; path=/`;
        } else {
            // Supprimer le cookie
            document.cookie = 'remember_email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
    });
}