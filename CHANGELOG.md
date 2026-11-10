# Changelog

Les changements notables de Petgarden sont consignés ici.

Ce fichier suit les principes de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/) et le projet vise le [versionnage sémantique](https://semver.org/lang/fr/).

## [Non publié]

### Ajouté

- MVC complet des 13 écrans de la maquette Figma : accueil, annonces filtrables, détail et candidature, candidatures reçues avec validation d'un gardien, mes candidatures, clôture d'une garde, avis croisés propriétaire / gardien, profil public, modération des avis et gestion des comptes.
- Vues calquées sur la maquette (Lora / Inter, palette PetGarden) avec en-tête, pied et fonctions d'affichage partagés (`vue/partials/`), feuille `public/assets/css/style.css` et script `public/assets/js/petgarden.js` (aperçu en direct, compteur, confirmations).
- Classe `Controleur` commune : session, pages protégées, protection CSRF, messages flash.
- Validation des données avant chaque écriture en base (inscription, annonce, candidature, avis), avec réaffichage des erreurs sans perte de la saisie.
- Message flash après chaque action : publication, candidature, validation d'un gardien, clôture d'une garde, avis, actions d'administration.
- Messages adaptés quand une liste est vide (annonces, candidatures, avis, profil).
- Logo PetGarden redimensionné pour le web (`public/assets/img/logo.png`).
- Checklists de fin de séance 1 et 2 et tâches des prochaines séances dans `CHECKLIST.md`.
- Liste publique des annonces ouvertes (`public/annonces.php`), inscription et connexion avec mots de passe hachés.
- Espace utilisateur (`accueil.php`), publication, liste et modification de ses annonces (`creerAnnonce.php`, `mesAnnonces.php`, `modifierAnnonce.php`) et déconnexion, issus du travail de Saobane.
- Architecture MVC : `model/`, `vue/`, `controleur/`, avec une page d'entrée par écran dans `public/`.
- Écrans complémentaires pour les éléments de la base absents de la maquette (`docs/maquette/petgarden-elements-bdd-absents.pdf` et `docs/maquette/figma-import/`).
- Documentation de présentation et de contribution au dépôt, et guide `docs/guide-mvc.md`.
- Checklist de suivi des tâches dans `CHECKLIST.md`.
- Dossier `docs/` pour la maquette Figma, les diagrammes UML et le MCD.
- Modèles de pull request et de signalement de bug.
- Configuration de base de l’éditeur et règles Git ignore.

### Modifié

- Noms de fichiers en minuscules (`connexion.php`, `mes_annonces.php`, `model/annonce.php`…) et SQL des modèles en minuscules.
- `public/index.php` affiche la page d'accueil de la maquette au lieu de rediriger vers les annonces ; l'ancien « Mon espace » est remplacé par le profil.
- Une annonce pourvue ou terminée n'est plus modifiable.
- Base de données enrichie avec les éléments de la maquette Figma : profil et usage des comptes, animaux, espèces, services et tarifs, réservations, planning, messagerie, factures et paiements, statut « brouillon » des annonces. Les clés étrangères des annonces, candidatures, réservations et messages passent en `on delete cascade` pour la suppression de compte. Le fichier de démonstration reproduit les écrans de la maquette. Voir `docs/correspondance-bdd-figma.md`.
- Fusion des travaux de Rayan et de Saobane : les fichiers de Saobane remplacent les fichiers correspondants (`config/database.php` avec la classe `Database`, modèles, contrôleurs, vues, pages de `public/`). Le dossier `modele/` devient `model/`.
- Le schéma SQL déménage dans `database/petgarden.sql`.
- Le serveur de développement pointe sur `public/` (`php -S localhost:8000 -t public`) et `public/index.php` redirige vers la liste des annonces.
- Le README indique PHP 7.4 minimum (testé avec PHP 8.3).

### Corrigé

- `database/petgarden.sql` ne s'importait pas : les tables étaient créées en `annonce` / `candidature` mais référencées en `annonces` / `candidatures`. Le nom et le prénom des comptes de démonstration étaient inversés.
- Les modèles utilisaient la colonne `id` alors que la base utilise `id_utilisateur` et `id_annonce`.
- `public/Inscription.php` chargeait `vue/inscription.php` au lieu de `vue/Inscription.php` : erreur 500 sous Linux, où la casse compte.
- `Utilisateur::seConnecter` comparait le mot de passe en clair alors que les comptes sont stockés hachés, ce qui empêchait toute connexion. `password_verify` est rétabli.
- Alignement des insertions SQL avec la colonne `mdp` et hachage des mots de passe de démonstration.

### Supprimé

- Anciens fichiers en casse mixte (`Connexion.php`, `mesAnnonces.php`, `creerAnnonce.php`…) et l'espace « Mon espace » (`accueil.php`), remplacés par les écrans de la maquette.
- Ancienne structure à plat (`index.php` à la racine, `FormulaireConnexion/`, `config/ConnexionBDD.php`, `sql/`) et première version MVC de Rayan (`app/`, puis `modele/`, `noyau/`), remplacées par le code de Saobane.
- `TODO.md` (remplacé par `CHECKLIST.md`) et `PROMPT.md` (prompt de reprise pour assistant de code, sans utilité dans le dépôt).
- Fichiers de Saobane non repris : `public/indx.php` (vide), `public/test-inscription.php` (créait un compte à chaque visite) et `public/css` (fichier vide).

### Prévu

- Suspension d'un compte depuis le back-office : champ `actif`, refus de connexion avec le message « Ce compte est suspendu ».
- Back-office des annonces : filtres (ville, statut, mot-clé), pagination et export qui conserve les filtres.
- Ces deux fonctions sont prévues en Symfony (`security.yaml`, `UserChecker`, Twig) ; le détail est dans `CHECKLIST.md`.

## [0.1.0] - 2026-10-20

### Ajouté

- Première structure PHP organisée autour de configuration, modèles, formulaires et vues.
- Script SQL initial avec les tables utilisateurs, annonces, candidatures et avis.
