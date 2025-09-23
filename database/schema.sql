CREATE DATABASE blog_db;
USE blog_db;

-- Table des utilisateurs
CREATE TABLE utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'user') DEFAULT 'user',
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des articles avec support média
CREATE TABLE articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255) NOT NULL,
  contenu TEXT NOT NULL,
  auteur_id INT NOT NULL,
  date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  media_path VARCHAR(255) DEFAULT NULL,
  media_type ENUM('image', 'video') DEFAULT NULL,
  FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des commentaires
CREATE TABLE commentaires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  contenu TEXT NOT NULL,
  auteur_id INT NOT NULL,
  article_id INT NOT NULL,
  date_commentaire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);
