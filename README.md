# BNGRC - Gestion des Dons pour les Sinistrés

Application web complète en PHP 8 utilisant le framework Flight (architecture MVC) et MySQL comme base de données.

## 📋 Description

Cette application permet de suivre les collectes et distributions de dons pour les sinistrés du BNGRC (Bureau National de Gestion des Risques et des Catastrophes).

### Fonctionnalités

- **Gestion des villes** : CRUD complet des villes sinistrées
- **Gestion des besoins** : Saisie des besoins par ville (nature, matériaux, argent)
- **Gestion des dons** : Enregistrement des dons disponibles
- **Attribution des dons** : Attribution d'un don à un besoin existant avec validation
- **Tableau de bord** : Vue d'ensemble avec statistiques et suivi des attributions

### Règles de gestion

- Les sinistrés sont regroupés par ville dans une région
- Les besoins peuvent être : nature (riz, huile...), matériaux (tôle, clous...), argent
- Validation : la quantité attribuée ne peut pas dépasser le don disponible
- Le type du don doit correspondre au type du besoin

## 🛠 Installation

### Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Composer

### Étapes d'installation

1. **Installer les dépendances**
   ```bash
   composer install
   ```

2. **Créer la base de données**
   ```sql
   CREATE DATABASE ETU4208_4256_4332;
   ```

3. **Importer le schéma**
   ```bash
   mysql -u root -p ETU4208_4256_4332 < config/schema.sql
   ```

4. **Configurer la base de données**
   - Modifier `config/database.php` si nécessaire
   - Par défaut : host=localhost, user=root, password=

5. **Lancer le serveur**
   ```bash
   php -S localhost:8000 -t public
   ```

6. **Accéder à l'application**
   - Ouvrir http://localhost:8000 dans votre navigateur

## 📁 Structure du projet

```
Projet-S3-Final/
├── app/
│   ├── controllers/     # Contrôleurs MVC
│   │   ├── DashboardController.php
│   │   ├── VilleController.php
│   │   ├── BesoinController.php
│   │   ├── DonController.php
│   │   └── AttributionController.php
│   ├── models/          # Modèles MVC
│   │   ├── Model.php
│   │   ├── Ville.php
│   │   ├── Besoin.php
│   │   ├── Don.php
│   │   └── Attribution.php
│   └── views/           # Vues
│       ├── layouts/
│       ├── dashboard/
│       ├── villes/
│       ├── besoins/
│       ├── dons/
│       └── attributions/
├── config/
│   ├── database.php     # Configuration base de données
│   └── schema.sql       # Schéma SQL
├── public/
│   ├── index.php       # Point d'entrée
│   └── assets/
│       ├── css/
│       └── js/
├── vendor/              # Dépendances Composer
├── composer.json
└── README.md
```

## 🎨 Design

- Interface moderne et responsive
- Utilise Bootstrap 5
- Couleurs adaptées au thème humanitaire
- Tableau de bord professionnel avec cartes statistiques

## 📊 Base de données

### Tables

- **villes** : Villes sinistrées (id, nom, region, description)
- **besoins** : Besoins par ville (id, ville_id, type_besoin, nom, quantite_requise, unite)
- **dons** : Dons disponibles (id, type_don, nom, quantite_disponible, unite, donateur)
- **attributions** : Attribution don → besoin (id, don_id, besoin_id, quantite_attribuee)

### Relations

- Une ville → plusieurs besoins
- Un besoin → plusieurs attributions
- Un don → peut être attribué plusieurs fois

## 🔧 Utilisation

### Créer une ville
1. Allez dans Gestion → Villes
2. Cliquez sur "Nouvelle ville"
3. Remplissez le formulaire

### Créer un besoin
1. Allez dans Gestion → Besoins
2. Cliquez sur "Nouveau besoin"
3. Sélectionnez une ville et le type de besoin

### Enregistrer un don
1. Allez dans Gestion → Dons
2. Cliquez sur "Nouveau don"
3. Remplissez les informations

### Attribuer un don à un besoin
1. Allez dans Gestion → Attributions
2. Cliquez sur "Nouvelle attribution"
3. Sélectionnez le don et le besoin
4. Indiquez la quantité (la validation bloque si quantité > don disponible)

## 📝 License

Projet S3 Design - Février 2026
