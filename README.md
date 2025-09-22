# Projet Blog – Gestion d'Articles et Commentaires

## Description
Ce projet est une application web de blog permettant de gérer des articles, des commentaires et des utilisateurs avec différents rôles (admin et user).
L'objectif principal est de pratiquer le développement full-stack : gestion de la base de données, logique métier côté serveur en PHP, et interface utilisateur responsive.

---

## Fonctionnalités

### Gestion des articles
- Les administrateurs peuvent **créer**, **modifier** et **supprimer** des articles.
- Les articles contiennent un **titre**, un **contenu**, un **auteur** et une **date de publication**.

### Gestion des utilisateurs
- Les utilisateurs peuvent se **connecter** et **s'inscrire**.
- Les rôles gérés :
  - **Admin** : accès complet à la gestion des articles.
  - **Utilisateur standard** : peut lire les articles et commenter.

### Gestion des commentaires
- Les utilisateurs connectés peuvent **ajouter des commentaires** à un article.
- Chaque commentaire contient le **contenu**, l’**auteur** et la **date de publication**.
- Les commentaires sont liés à l’article correspondant (`article_id`) et à l’utilisateur (`auteur_id`).

---

## Structure du projet
<pre>
.blog-project
├── admin
│   ├── dashboard.php
│   ├── manage_posts.php
│   └── manage_users.php
├── article.php
├── classes
│   ├── commentaire.php
│   ├── connexion.php
│   ├── Post.php
│   └── User.php
├── cookie.txt
├── handlers
│   ├── commentaire_handlers.php
│   ├── post_handlers.php
│   └── user_handlers.php
├── includes
│   ├── footer.php
│   ├── functions.php
│   └── header.php
├── index.php
├── login.php
├── public
│   ├── footer.css
│   ├── header.css
│   ├── index.css
│   └── js
├── README.md
├── register.php
└── uploads
</pre>

---

## Technologies utilisées
- **PHP** : logique serveur, gestion des sessions, interactions avec la base de données.
- **MySQL** : base de données relationnelle pour les articles, utilisateurs et commentaires.
- **HTML / CSS** : interface utilisateur responsive et moderne.
- **JavaScript** : interactions dynamiques (affichage formulaire commentaires).

---

## Installation et configuration
### Prérequis
- Serveur web (Apache. Nginx)
- PHP 7.4
- MySQL
- Composer mais Optionelle

### Étapes d'installation
1. Cloner le projet :
   ```bash
   git clone https://github.com/exauceaniti/blog-project.git


2. Configurer la base de données dans classes/Connexion.php.
- Cree une base de donnee MySQL
- Importez le fichier SQL fournie (database/shema.sql) ou executer les requettes suivantes:
```bash
CREATE DATABASE blog_db;
USE blog_db;

CREATE TABLE utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'user') DEFAULT 'user',
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255) NOT NULL,
  contenu TEXT NOT NULL,
  auteur_id INT NOT NULL,
  date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE commentaires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  contenu TEXT NOT NULL,
  auteur_id INT NOT NULL,
  article_id INT NOT NULL,
  date_commentaire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);
```
3. Configurer les paramètres de connexion :
- Éditer le fichier classes/Connexion.php avec vos paramètres de base de données :
```bash
private $host = 'localhost';
private $dbname = 'blog_db';
private $username = 'votre_utilisateur';
private $password = 'votre_mot_de_passe';
```

4. Lancer le projet :

- Placer le projet dans le dossier de votre serveur web (ex: htdocs pour XAMPP)

- Démarrer votre serveur web et MySQL

- Accéder à l'application via : http://localhost/blog-project

- Créer le premier utilisateur admin :
Exécuter cette requête SQL pour créer un administrateur (mot de passe: "admin123") :
```bash
INSERT INTO utilisateurs (nom, email, password, role) 
VALUES ('Administrateur', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

## Utilisation
### Pour les visiteurs 

- Consulter les articles sur la page d'accueil

- Lire les articles complets en cliquant sur "Lire la suite"

- Voir les commentaires associés à chaque article

### Pour les utilisateurs connectés

- S'inscrire et se connecter

- Commenter les articles

- Modifier leur profil (si implémenté)

### Pour les administrateurs
- Accéder au dashboard administrateur

- Créer, modifier et supprimer des articles

- Gérer les utilisateurs (changer les rôles, supprimer)

- Modérer les commentaires


## Améliorations futures

- Système de modération des commentaires

- Fonctionnalité de recherche d'articles

- Système de catégories/tags pour les articles

- Upload d'images pour les articles

- Système de likes/étoiles pour les articles

- Newsletter pour les nouveaux articles

- Interface d'administration plus complète

- API REST pour applications mobiles

- Intégration avec les réseaux sociaux

- Système de cache pour améliorer les performances

### Sécurité

- Protection contre les injections SQL avec les requêtes préparées

- Hashage des mots de passe avec l'algorithme bcrypt

- Validation des données côté serveur

- Protection contre les failles XSS avec htmlspecialchars()

- Gestion des sessions sécurisées


### Auteur
Exauce Aniti

Email: [exauceaniti@gmail.com]

GitHub: [https://github.com/exauceaniti]

