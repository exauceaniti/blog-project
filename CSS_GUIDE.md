# 📚 Guide CSS Professionnel - Blog Project

## 🎯 Architecture CSS

Ton projet utilise une **architecture modulaire BEM-like** avec variables CSS et utilitaires réutilisables.

```
assets/css/
├── core/
│   ├── variables.css    ← Palette de design, espacements, ombres
│   ├── reset.css        ← Normalisation CSS
│   ├── typography.css   ← Typographie globale
│   ├── utilities.css    ← Classes utilitaires globales
│   ├── mixins.css       ← Patterns réutilisables (nouveaux ✨)
│   └── helpers.css      ← Classes helpers (nouveaux ✨)
├── layout/              ← Composants de layout (header, footer, grid)
├── components/          ← Composants UI réutilisables
├── pages/               ← Styles spécifiques aux pages
└── main.css             ← Importe tout
```

---

## ✨ Comment éviter la répétition

### 1️⃣ **Utilise les Variables CSS**

❌ **MAU vais** (répétition):

```css
.card-header {
  padding: 1rem;
  background: #ffffff;
  border-radius: 6px;
}

.card-body {
  padding: 1rem;
  background: #ffffff;
  border-radius: 6px;
}
```

✅ **BON** (utilise les variables):

```css
.card-header {
  padding: var(--space-4);
  background: var(--bg-primary);
  border-radius: var(--radius-md);
}

.card-body {
  padding: var(--space-4);
  background: var(--bg-primary);
  border-radius: var(--radius-md);
}
```

### 2️⃣ **Utilise les Classes Mixins (composables)**

❌ **MAUVAIS** (répétition):

```html
<div style="display: flex; align-items: center; gap: 8px;">
  <img src="..." />
  <p>Nom</p>
</div>

<div style="display: flex; align-items: center; gap: 8px;">
  <img src="..." />
  <p>Autre</p>
</div>
```

✅ **BON** (utilise `flex-row`):

```html
<div class="flex-row gap-2">
  <img src="..." />
  <p>Nom</p>
</div>

<div class="flex-row gap-2">
  <img src="..." />
  <p>Autre</p>
</div>
```

### 3️⃣ **Utilise les Classes Helpers**

❌ **MAUVAIS** (CSS personnalisé pour chaque élément):

```css
.sidebar {
  margin-bottom: 16px;
  margin-top: 8px;
  padding: 16px;
}

.article {
  margin-bottom: 16px;
  margin-top: 8px;
  padding: 16px;
}
```

✅ **BON** (classe helper):

```html
<aside class="sidebar mb-4 mt-2 p-4">...</aside>
<article class="article mb-4 mt-2 p-4">...</article>
```

---

## 📖 Exemples d'Utilisation

### Card avec Image

```html
<div class="card card--interactive">
  <img src="..." alt="..." class="w-full rounded-lg" />
  <div class="p-4">
    <h3 class="font-bold text-primary mb-2">Titre</h3>
    <p class="text-secondary text-sm">Description</p>
    <button class="btn btn--primary mt-4 w-full">Lire plus</button>
  </div>
</div>
```

### Form Layout

```html
<form class="flex-col gap-4 p-4">
  <div class="flex-col gap-2">
    <label class="font-medium text-primary">Email</label>
    <input type="email" class="border rounded p-3" placeholder="..." />
  </div>

  <div class="flex-row gap-2">
    <button type="submit" class="btn btn--primary flex-1">Envoyer</button>
    <button type="reset" class="btn btn--secondary flex-1">Annuler</button>
  </div>
</form>
```

### Admin Dashboard Card

```html
<div class="card shadow-md">
  <div class="flex-row justify-between items-center mb-4">
    <h2 class="font-bold text-2xl">Posts</h2>
    <a href="/admin/posts/create" class="btn btn--primary btn--sm">Créer</a>
  </div>

  <table class="w-full">
    <!-- contenu -->
  </table>
</div>
```

### Notification/Alert

```html
<!-- Success -->
<div class="card bg-success p-3 rounded-md">
  <p class="text-white font-medium">✓ Opération réussie!</p>
</div>

<!-- Error -->
<div class="card bg-error p-3 rounded-md">
  <p class="text-white font-medium">✗ Une erreur est survenue</p>
</div>
```

---

## 📦 Classes Disponibles

### Spacing (Margin & Padding)

```
m-0, m-1, m-2, m-3, m-4, m-6, m-8      // margin
mt-1, mt-2, mt-3, mt-4, mt-6, mt-8      // margin-top
mb-1, mb-2, mb-3, mb-4, mb-6, mb-8      // margin-bottom
mx-auto                                   // center horizontalement
p-1, p-2, p-3, p-4, p-6, p-8            // padding
px-2, px-3, px-4                         // padding horizontal
py-1, py-2, py-3, py-4                   // padding vertical
```

### Display & Layout

```
d-none, d-block, d-inline, d-inline-block
d-flex, d-grid
flex-wrap, flex-nowrap
items-start, items-center, items-end
justify-start, justify-center, justify-end, justify-between
gap-2, gap-3, gap-4, gap-6
```

### Colors

```
text-primary, text-secondary, text-tertiary, text-accent
text-success, text-error, text-warning
bg-primary, bg-secondary, bg-tertiary, bg-dark
bg-success, bg-error, bg-warning
```

### Typography

```
font-bold, font-semibold, font-medium, font-normal
text-center, text-left, text-right
italic
```

### Borders & Radius

```
border, border-top, border-bottom
border-light, border-dark
rounded, rounded-sm, rounded-md, rounded-lg, rounded-full
```

### Shadows

```
shadow-none, shadow-xs, shadow-sm, shadow, shadow-md, shadow-lg
```

### Width & Height

```
w-full, w-half, w-third, w-quarter
max-w-sm, max-w-md, max-w-lg, max-w-xl, max-w-full
h-full, h-screen
```

### Other

```
cursor-pointer, cursor-default, cursor-not-allowed
overflow-hidden, overflow-auto, overflow-x-auto
opacity-0, opacity-25, opacity-50, opacity-75, opacity-100
z-0, z-10, z-20, z-modal, z-toast
```

---

## 🎨 Variables Disponibles

Toutes les variables CSS sont définies dans `core/variables.css` et accessibles partout:

```css
/* Couleurs */
--primary-500, --primary-600, --primary-700, ...
--color-primary, --color-primary-hover
--text-primary, --text-secondary, --text-tertiary
--bg-primary, --bg-secondary, --bg-tertiary

/* Espacements */
--space-1 (4px), --space-2 (8px), --space-3 (12px), --space-4 (16px), ...

/* Typographie */
--text-sm, --text-base, --text-lg, --text-xl, --text-2xl, ...
--leading-tight, --leading-normal, --leading-relaxed

/* Rayons */
--radius, --radius-sm, --radius-md, --radius-lg, --radius-full

/* Ombres */
--shadow-sm, --shadow, --shadow-md, --shadow-lg

/* Transitions */
--transition-fast, --transition, --transition-slow

/* Z-index */
--z-dropdown, --z-modal, --z-toast
```

---

## 🚀 Bonnes Pratiques

1. **Utilise les variables** pour tout (couleurs, espacements, rayons)
2. **Compose les classes** au lieu de créer du CSS personnalisé
3. **Réutilise les mixins** (flex-row, card, box, etc.)
4. **Utilise les helpers** pour l'espacemnt et l'alignement
5. **Évite le CSS inline** - utilise les classes helpers
6. **BEM pour les composants** - utilisez des noms clairs (`.card`, `.btn--primary`)

---

## ❌ À Éviter

```css
/* ❌ Mauvais */
.my-element {
  padding: 1rem;
  margin: 8px;
  background: #3b82f6;
  color: #1e293b;
}

/* ✅ Bon */
.my-element {
  padding: var(--space-4);
  margin: var(--space-2);
  background: var(--color-primary);
  color: var(--text-primary);
}
```

```html
<!-- ❌ Mauvais - CSS inline -->
<div style="display: flex; gap: 8px; margin-top: 16px;">
  <!-- ✅ Bon - Utilise les classes -->
  <div class="flex-row gap-2 mt-4"></div>
</div>
```

---

**Maintenant tu as un système CSS professionnel et sans répétitions! 🎉**
