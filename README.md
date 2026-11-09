# Petgarden

Petgarden est un projet étudiant de site de garde d'animaux (pet sitting). Il met en relation des propriétaires et des gardiens autour d'annonces de garde, de candidatures et d'avis.

> **État du projet :** prototype en développement. Consultation des annonces, inscription et connexion sont implémentées ; leur validation sur une instance MySQL locale reste à confirmer. Voir [CHECKLIST.md](CHECKLIST.md).

## Fonctionnalités

- Comptes utilisateur : inscription, connexion, déconnexion, espace personnel
- Consultation publique des annonces de garde ouvertes
- Publication, liste et modification de ses propres annonces
- _Prévu :_ candidatures de gardiens, avis après une garde

## Technologies

- PHP 7.4 ou plus récent (testé avec PHP 8.3), extensions PDO MySQL et mbstring
- MySQL ou MariaDB
- HTML / CSS

## Architecture MVC

```text
.
├── model/           # M — accès à la base de données (une classe par table)
├── vue/             # V — pages HTML (une vue par écran)
├── controleur/      # C — logique de chaque écran : formulaire, validation, appel du modèle
├── public/          # Racine web : une page d'entrée par écran (annonces.php, Connexion.php…) et assets/
├── config/
│   └── database.php # Classe Database : connexion à la base (4 valeurs à adapter)
├── database/
│   └── petgarden.sql# Schéma et données de démonstration
├── docs/            # Maquette Figma, UML, MCD, guide MVC
├── CHANGELOG.md     # Historique des changements
├── CHECKLIST.md     # Suivi des tâches
└── CONTRIBUTING.md  # Flux Git et conventions
```

Le trajet d'une page, par exemple `mesAnnonces.php` :

```text
public/mesAnnonces.php  →  AnnonceController  →  Annonce (model)  →  vue/mesAnnonces.php
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

5. Ouvrir <http://localhost:8000> (redirige vers la liste des annonces).

> Les comptes de démonstration du SQL utilisent des mots de passe de test (`amine123`, `sarah123`, `lucas123`, `emma123`, `admin123`, ainsi que `lea123`, `thomas123`, `camille123`, `julien123`, `aline123` pour les comptes de la maquette) réservés au développement local.

## Documentation

- [docs/guide-mvc.md](docs/guide-mvc.md) : comprendre et étendre le MVC
- [docs/correspondance-bdd-figma.md](docs/correspondance-bdd-figma.md) : ce que la base et la maquette ont en commun ou non
- [docs/](docs/) : maquette Figma, diagrammes UML, MCD
- [CHECKLIST.md](CHECKLIST.md) : tâches en cours et terminées
- [CHANGELOG.md](CHANGELOG.md) : historique des versions
- [CONTRIBUTING.md](CONTRIBUTING.md) : branches, commits, pull requests

## Équipe

- Rayan
- Saobane
- Predipe
