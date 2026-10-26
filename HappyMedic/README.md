# HappyMedic - interface web

Interface web d'un **distributeur automatisé de médicaments**, réalisée dans le cadre du projet de BTS Systèmes Numériques (option B Électronique et Communications) au lycée Laetitia Bonaparte d'Ajaccio, en 2024.

Le projet a été conçu en équipe de 4. **J'ai réalisé la partie web** (PHP / MySQL) : gestion des utilisateurs, des ordonnances et des stocks, et le service qui transmet l'ordonnance d'un patient au distributeur. Le matériel du distributeur (électronique, mécanique, programme embarqué) est l'œuvre des autres membres de l'équipe et n'est pas inclus dans ce dépôt.

## Fonctionnalités

- Connexion avec un compte, et menu adapté au statut de l'utilisateur (`manitou`, `secretaire`, `infirmier`, `medecin`, `patient`)
- Création de patients et de comptes du personnel
- Saisie d'ordonnances par un médecin (quantités de chaque médicament)
- Consultation d'une ordonnance à partir du prénom du patient
- Gestion des stocks de médicaments
- Point d'accès JSON (`vconsult.php`) interrogé par le distributeur pour récupérer l'ordonnance à délivrer

## Technologies

PHP 8 (mysqli), MySQL / MariaDB, HTML / CSS, un peu de JavaScript (consultation).

## Installation (XAMPP)

1. Installer [XAMPP](https://www.apachefriends.org/) et démarrer **Apache** et **MySQL**.
2. Copier le dossier du projet dans `C:\xampp\htdocs\HappyMedic`.
3. Ouvrir `http://localhost/phpmyadmin`, onglet **Importer**, et choisir `sql/medi.sql`. La base `medi` est créée avec des données de démonstration.
4. Copier `includes/config.example.php` en `includes/config.php` et adapter les identifiants de la base si besoin (par défaut : `root` sans mot de passe, ce qui convient à XAMPP en local uniquement).
5. Ouvrir `http://localhost/HappyMedic/`.

### Comptes de démonstration

| E-mail | Mot de passe | Statut |
|---|---|---|
| ag@tf1.fr | pwdasterix | manitou (administrateur) |
| og@tf1.fr | obelix | medecin |
| pano@tf1.fr | pano | medecin |
| jc@tf1.fr | cesar | patient |

Pour tester la consultation, saisir par exemple le prénom `obelix`.

## Organisation du dépôt

```
HappyMedic/
├── index.php                 Connexion
├── menu.php                  Menu selon le statut
├── nouveau_patient.php       Inscription d'un patient
├── nouveau_personnel.php     Création d'un compte du personnel
├── nouvelle_ordonnance.php   Saisie d'une ordonnance
├── consultation.php          Consultation d'une ordonnance
├── stocks.php                Gestion des stocks
├── vconsult.php              Point d'accès JSON utilisé par le distributeur
├── deconnexion.php
├── includes/                 Connexion à la base, session, en-tête / pied de page
├── assets/style.css          Feuille de style
└── sql/medi.sql              Structure et données de démonstration
```

## Base de données

| Table | Contenu |
|---|---|
| `personne` | Utilisateurs : nom, prénom, e-mail, mot de passe, statut |
| `ordonnance` | Quantité de chaque médicament (`s1`, `s2`, `v`, `b`, `r`, `j`, `c1`, `c2`) pour un patient |
| `stock` | Médicaments disponibles et leur quantité |

Les quantités `s1` et `s2` sont saisies en ml puis converties en unités du distributeur (facteur 270).

## Point d'accès du distributeur

`POST /vconsult.php` avec le champ `cb` (prénom du patient). Réponse :

```json
{"s1":270,"s2":540,"v":3,"b":4,"r":6,"j":5,"c1":1,"c2":1,"msg":"id personne = 2"}
```

Si aucune ordonnance n'existe, la réponse est un code 404 avec `{"erreur":"..."}`.

## Limites connues et améliorations prévues

Ce projet a été réalisé pour un BTS, sans objectif de mise en production. Les points suivants sont connus :

- Les mots de passe sont stockés **en clair** (à remplacer par `password_hash()` / `password_verify()`).
- Les pages vérifient que l'utilisateur est connecté, mais **pas son statut** : le menu masque des liens, sans les protéger.
- `vconsult.php` n'est **pas authentifié** (il doit rester accessible au distributeur ; une clé partagée serait à prévoir).
- La connexion à la base utilise le compte `root` sans mot de passe (à remplacer par un compte dédié aux droits limités).
- Pas de protection contre les attaques CSRF sur les formulaires.

Les requêtes SQL sont préparées et les affichages sont échappés (`htmlspecialchars`).

## Auteur

Matteo Blanchard
