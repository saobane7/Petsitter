# Petgarden

Petgarden est un projet étudiant de site de garde d’animaux. Il vise à mettre en relation des propriétaires et des gardiens autour d’annonces de garde, de candidatures et d’avis.

> **État du projet :** prototype en développement. Le dépôt contient le schéma SQL et les premiers fichiers PHP, mais l’installation et les parcours d’inscription, de connexion et de consultation des annonces ne sont pas encore opérationnels. Voir [TODO.md](TODO.md) pour le bilan actuel.

## Fonctionnalités prévues

- Comptes utilisateur et authentification
- Publication et consultation d’annonces de garde
- Candidatures de gardiens
- Avis après une garde

## Technologies

- PHP
- MySQL
- HTML

## Structure actuelle

```text
.
├── config/                  # Connexion à la base de données
├── FormulaireConnexion/     # Traitement de connexion (en cours)
├── modele/                  # Modèles PHP
├── sql/petgarden.sql        # Schéma et données de démonstration
├── vue/                     # Vues (à compléter)
├── index.php                # Point d’entrée actuel, encore une page de test
└── TODO.md                  # État de la checklist et tâches restantes
```

## Démarrage

### Prérequis

- PHP avec PDO MySQL activé
- MySQL ou MariaDB
- Un serveur PHP local ou un serveur web configuré pour PHP

### Installation prévue

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/saobane7/Petsitter.git
   cd Petsitter
   ```

2. Créer la base en important `sql/petgarden.sql` dans MySQL.
3. Configurer les identifiants de base de données localement dans `config/ConnexionBDD.php`.
4. Démarrer un serveur PHP à la racine :

   ```bash
   php -S localhost:8000
   ```

5. Ouvrir <http://localhost:8000>.

> **À savoir :** ces étapes décrivent la cible de développement, mais ne fonctionnent pas encore de bout en bout. La configuration PHP comporte actuellement une erreur de syntaxe et les données SQL de démonstration utilisent un nom de colonne différent du schéma. Suivre [TODO.md](TODO.md) avant de considérer l’application installable.

## Contribution

Consultez [CONTRIBUTING.md](CONTRIBUTING.md) pour le flux Git, les conventions de branches et les vérifications à effectuer avant une pull request.

## Historique des versions

Les changements notables sont consignés dans [CHANGELOG.md](CHANGELOG.md).

## Équipe

- Rayan
- Saobane
- Predipe
