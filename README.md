# Système de Gestion de Laboratoire Médical

## Description du Projet

Ce projet est un système complet de gestion pour un laboratoire médical comprenant :
- Interface patient pour consultation et demandes
- Interface administrateur pour la gestion
- Système de gestion des analyses et résultats
- Service de prélèvement à domicile

## Structure du Projet

```
laboratoire/
├── admin/
│   ├── caissier/          # Interface caissier
│   └── docteur/           # Interface docteur/admin
├── utilisateur/           # Interface patient
├── includes/              # Fichiers communs
├── photos/               # Images et assets
├── sql/                  # Scripts de base de données
└── README.md
```

## Base de Données

### Tables Principales

1. **patient** - Informations des patients
2. **services** - Analyses disponibles
3. **categorie** - Types d'analyses
4. **dossier** - Dossiers médicaux
5. **details** - Détails des analyses
6. **users** - Utilisateurs du système

### Installation de la Base de Données

1. Créer une base de données MySQL nommée `labonew`
2. Importer le fichier `sql/sql.sql`
3. Exécuter les corrections avec `sql/database_fixes.sql`

## Fonctionnalités Corrigées

### Interface Patient

✅ **Inscription** (`utilisateur/register_process.php`)
- Validation complète des données
- Vérification de l'unicité de l'email
- Hashage sécurisé des mots de passe
- Messages d'erreur détaillés

✅ **Connexion** (`utilisateur/login.php`)
- Authentification sécurisée
- Gestion des sessions
- Messages d'erreur appropriés
- Redirection vers le tableau de bord

✅ **Tableau de Bord** (`utilisateur/dashboard.php`)
- Affichage des informations personnelles
- Historique des dossiers médicaux
- Statut des demandes

✅ **Déconnexion** (`utilisateur/logout.php`)
- Destruction sécurisée des sessions

### Interface Caissier

✅ **Ajout de Patient** (`admin/caissier/ajouter_patient.php`)
- Formulaire complet avec validation
- Vérification des doublons
- Messages de confirmation/erreur

### Améliorations de Sécurité

✅ **Mots de passe**
- Hashage avec `password_hash()`
- Vérification avec `password_verify()`

✅ **Validation des données**
- Échappement HTML avec `htmlspecialchars()`
- Validation des emails et téléphones
- Requêtes préparées (protection SQL injection)

✅ **Gestion des sessions**
- Sessions sécurisées
- Vérification d'authentification
- Déconnexion propre

## Configuration

### Fichier de Connexion (`includes/connexion.php`)

```php
<?php
$host = 'localhost';
$dbname = 'labonew';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
```

## Problèmes Résolus

### 1. Formulaires Non Fonctionnels
- ❌ **Avant** : Formulaires sans traitement backend
- ✅ **Après** : Traitement complet avec validation

### 2. Sécurité des Mots de Passe
- ❌ **Avant** : Mots de passe en texte clair
- ✅ **Après** : Hashage sécurisé avec PHP

### 3. Gestion des Erreurs
- ❌ **Avant** : Pas de validation ni messages d'erreur
- ✅ **Après** : Validation complète et messages utilisateur

### 4. Navigation
- ❌ **Avant** : Liens cassés entre pages
- ✅ **Après** : Navigation cohérente et fonctionnelle

### 5. Base de Données
- ❌ **Avant** : Contraintes manquantes, index absents
- ✅ **Après** : Structure optimisée avec contraintes

## Utilisation

### Pour les Patients
1. S'inscrire via `utilisateur/register_process.php`
2. Se connecter via `utilisateur/compte.php`
3. Accéder au tableau de bord
4. Demander des services à domicile

### Pour les Administrateurs
1. Accéder via `admin/docteur/interface.php`
2. Gérer les patients, analyses, tarifs
3. Consulter les statistiques

### Pour les Caissiers
1. Accéder via l'interface caissier
2. Gérer les patients
3. Consulter les tarifs et notifications

## Sécurité

- Toutes les requêtes utilisent des requêtes préparées
- Mots de passe hashés avec `password_hash()`
- Validation côté serveur pour tous les formulaires
- Protection contre XSS avec `htmlspecialchars()`
- Gestion sécurisée des sessions

## Technologies Utilisées

- **Backend** : PHP 7.4+
- **Base de données** : MySQL 5.7+
- **Frontend** : HTML5, CSS3, JavaScript
- **Sécurité** : PDO, password_hash(), sessions PHP

## Installation

1. Cloner le projet dans votre serveur web
2. Configurer la base de données MySQL
3. Importer les fichiers SQL
4. Modifier `includes/connexion.php` si nécessaire
5. Accéder au projet via votre navigateur

Le système est maintenant entièrement fonctionnel avec toutes les corrections de sécurité et de fonctionnalité appliquées.