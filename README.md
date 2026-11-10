# Petgarden

Petgarden est un projet étudiant de site de garde d'animaux (pet sitting). Il met en relation des propriétaires et des gardiens autour d'annonces de garde, de candidatures et d'avis.

> **État du projet :** les 13 écrans de la maquette Figma sont implémentés en MVC et testés sur MariaDB 10.11 / PHP 8.3. Voir [CHECKLIST.md](CHECKLIST.md).

## Fonctionnalités

- Comptes : inscription, connexion, déconnexion, profil public (note moyenne, gardes réalisées, avis reçus)
- Annonces : accueil, liste filtrable (type d'animal, ville), détail, publication, modification, mes annonces avec statistiques
- Candidatures : postuler, candidatures reçues, validation d'un gardien (les autres sont refusées), mes candidatures
- Avis : après une garde terminée, le propriétaire et le gardien se notent de 1 à 5
- Administration : modération des avis, statistiques globales, rôles et suppression des comptes
- Protection CSRF sur tous les formulaires

## Technologies

- PHP 7.4 ou plus récent (testé avec PHP 8.3), extensions PDO MySQL et mbstring
- MySQL ou MariaDB
- HTML / CSS

## Architecture MVC

```text
.
├── model/           # M — accès à la base (utilisateur, annonce, candidature, avis)
├── vue/             # V — une vue par écran de la maquette + partials/ (en-tête, pied, fonctions d'affichage)
├── controleur/      # C — controleur.php (session, accès, csrf) + un contrôleur par domaine
├── public/          # Racine web : une page d'entrée par écran (annonces.php, connexion.php…) et assets/
├── config/
│   └── database.php # Classe Database : connexion à la base (4 valeurs à adapter)
├── database/
│   └── petgarden.sql# Schéma et données de démonstration
├── docs/            # Maquette Figma, UML, MCD, guide MVC
├── CHANGELOG.md     # Historique des changements
├── CHECKLIST.md     # Suivi des tâches
└── CONTRIBUTING.md  # Flux Git et conventions
```

Le trajet d'une page, par exemple `mes_annonces.php` :

```text
public/mes_annonces.php  →  AnnonceController  →  Annonce (model)  →  vue/mes_annonces.php
   (point d'entrée)         (contrôleur)         (requête SQL)        (HTML affiché)
```

Pour ajouter une page, voir le [guide MVC](docs/guide-mvc.md).

## Démarrage

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/saobane7/Petsitter.git
   cd Petsitter
   ```

2. Créer la base en important `database/petgarden.sql` dans MySQL. Pour réimporter après une mise à jour du fichier, supprimer d'abord la base avec `drop database petgarden;`.
3. Ouvrir `config/database.php` et adapter si besoin les 4 valeurs au début de la classe `Database` (hôte, nom de la base, utilisateur, mot de passe). Par défaut : `localhost`, `petgarden`, `root`, mot de passe vide. Ne commitez jamais un vrai mot de passe.
4. Démarrer le serveur PHP en pointant sur `public/` :

   ```bash
   php -S localhost:8000 -t public
   ```

5. Ouvrir <http://localhost:8000> (page d'accueil).

> Comptes de démonstration (développement local uniquement) : `amine.diallo@gmail.com` / `amine123`, `sarah.martin@yahoo.fr` / `sarah123`, `lucas.bernard@outlook.com` / `lucas123`, `emma.petit@gmail.com` / `emma123`, et l'administrateur `admin.petsitter@yahoo.fr` / `admin123`.

## Documentation

- [docs/guide-mvc.md](docs/guide-mvc.md) : comprendre et étendre le MVC
- [docs/](docs/) : maquette Figma, diagrammes UML, MCD
- [CHECKLIST.md](CHECKLIST.md) : tâches en cours et terminées
- [CHANGELOG.md](CHANGELOG.md) : historique des versions
- [CONTRIBUTING.md](CONTRIBUTING.md) : branches, commits, pull requests

## Équipe

- Rayan
- Saobane
- Predipe
