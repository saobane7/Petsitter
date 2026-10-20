# Changelog

Les changements notables de Petgarden sont consignés ici.

Ce fichier suit les principes de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/) et le projet vise le [versionnage sémantique](https://semver.org/lang/fr/).

## [Non publié]

### Ajouté

- Première page listant les annonces ouvertes, ainsi que les formulaires d'inscription et de connexion.
- Gestion de session, jetons CSRF et déconnexion par requête POST.
- Création de compte avec validation et stockage des mots de passe hachés.
- Documentation de présentation et de contribution au dépôt.
- État de la checklist projet dans `TODO.md`.
- Modèles de pull request et de signalement de bug.
- Configuration de base de l’éditeur et règles Git ignore.

### À corriger

- Vérifier la connexion et les parcours PHP sur une instance MySQL locale ; voir `TODO.md`.

### Corrigé

- Correction de la configuration PDO et de la syntaxe de `config/ConnexionBDD.php`.
- Alignement des insertions SQL avec la colonne `mdp` et hachage des mots de passe de démonstration.
- Correction des getters de `Utilisateur` et de la table utilisée par l'authentification.

## [0.1.0] - 2026-10-20

### Ajouté

- Première structure PHP organisée autour de configuration, modèles, formulaires et vues.
- Script SQL initial avec les tables utilisateurs, annonces, candidatures et avis.
