# ClubHub — Plateforme de gestion des Clubs & Événements (PHP MVC)

ClubHub est une plateforme web (PHP orienté objet) permettant de gérer les **clubs** et leurs **événements** au sein d’un établissement : inscription des étudiants, gestion des membres, création d’événements par les présidents, participation, avis/étoiles, articles post-événements, administration globale, etc.

> Objectif : un code **lisible**, **maintenable** et **sécurisé** basé sur **MVC + POO**, avec un moteur de templates, PostgreSQL, logs et bonnes pratiques (SOLID/DRY).

---

## ✨ Fonctionnalités

### 👩‍🎓 Étudiant
- Inscription / Connexion
- Consulter la liste des clubs
- Consulter la page d’un club (nom, description, président, membres)
- Rejoindre un club (**1 seul club par étudiant**)
- Voir les événements d’un club
- S’inscrire à un événement
- Laisser un avis + note en étoiles **après participation**
- Consulter les articles publiés par un club sur les événements passés

### 👑 Président de club (est aussi un étudiant)
- Créer un événement (titre, description, date, lieu, images)
- Modifier / Supprimer ses événements
- Voir la liste des participants à ses événements
- Publier un article sur un événement passé (texte + images)
- Gérer les membres (visualiser la liste, respecter **max 8 membres**)

### 🛠️ Administration
- Créer **4 à 6 clubs** pour l’établissement
- Modifier un club (nom, description, président)
- Supprimer un club
- Visualiser les membres de chaque club
- Voir tous les événements créés
- Gérer les étudiants (visualiser / modifier / supprimer)

---

## ✅ Contraintes & Règles Métier
- **Max 8 membres** par club
- **1 étudiant = 1 club**
- **Le premier étudiant** inscrit dans un club devient **automatiquement président**
- **Seul le président** peut créer des événements pour le club
- Un événement contient : **titre, date, lieu, description, images**
- Avis + étoiles possibles **uniquement après participation**
- Articles post-événements : **texte + images**

---

## 🎁 Bonus (Optionnel)
- Design Patterns : **Repository Pattern**, **Service Container**
- Notifications email :
  - Inscription dans un club
  - Participation à un événement
  - Création d’un nouvel événement (par un président)
- Accès PWA (Progressive Web App)
- Export PDF d’un événement + liste des participants

---

## 🧱 Stack Technique
- **PHP 8+** (POO)
- **Architecture MVC** (Router + Controllers + Models + Views)
- **PostgreSQL**
- **Moteur de templates** : Twig *ou* Blade (selon choix du projet)
- **Logs** : fichiers (storage/logs)
- Sécurité :
  - Validation serveur (XSS, CSRF)
  - Requêtes préparées (SQL Injection)
  - Contrôle d’accès (roles)

---

## 📁 Structure du Projet (exemple recommandé)
clubhub/
public/
index.php
.htaccess
assets/
app/
Controllers/
Models/
Repositories/
Services/
Middlewares/
Views/
core/
Router.php
Database.php
Auth.php
Csrf.php
Validator.php
Logger.php
ErrorHandler.php
config/
config.php
database.php
storage/
logs/
cache/
uploads/
database/
schema.sql
docs/
uml/
planning/
.env
composer.json
README.md


---

## 🚀 Installation & Lancement

### 1) Pré-requis
- PHP 8+
- Composer
- PostgreSQL
- Serveur Apache/Nginx **ou** serveur PHP built-in
- Extensions PHP recommandées : `pdo`, `pdo_pgsql`

### 2) Cloner & installer les dépendances
```bash
git clone <repo>
cd clubhub
composer install
