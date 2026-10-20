# Petgarden

Petgarden est un projet étudiant de site de garde d’animaux. Il vise à mettre en relation des propriétaires et des gardiens autour d’annonces de garde, de candidatures et d’avis.

> **État du projet :** prototype en développement. Un premier parcours de consultation des annonces, d’inscription et de connexion est implémenté ; son intégration à une base MySQL doit encore être vérifiée sur les postes de l’équipe. Voir [TODO.md](TODO.md) pour le bilan actuel.

## Fonctionnalités prévues

- Comptes utilisateur et authentification
- Publication et consultation d’annonces de garde
- Candidatures de gardiens
- Avis après une garde

## Technologies

- PHP 7.3 ou plus récent
- MySQL
- HTML

## Structure actuelle

```text
.
├── config/                  # Connexion à la base de données
├── FormulaireConnexion/     # Services d’inscription et de connexion
├── modele/                  # Modèles utilisateur et annonce
├── sql/petgarden.sql        # Schéma et données de démonstration
├── vue/                     # Vues (à compléter)
├── index.php                # Point d’entrée et routes du premier parcours
└── TODO.md                  # État de la checklist et tâches restantes
```

## Démarrage

### Prérequis

- PHP 7.3 ou plus récent avec les extensions PDO MySQL et mbstring
- MySQL ou MariaDB
- Un serveur PHP local ou un serveur web configuré pour PHP

### Installation prévue

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/saobane7/Petsitter.git
   cd Petsitter
   ```

2. Créer la base en important `sql/petgarden.sql` dans MySQL.
3. Configurer la connexion avec les variables d’environnement suivantes (adaptez les valeurs à votre installation) :

   ```bash
   export PETGARDEN_DB_HOST=127.0.0.1
   export PETGARDEN_DB_NAME=petgarden
   export PETGARDEN_DB_USER=root
   export PETGARDEN_DB_PASSWORD=''
   ```

   Le code utilise les valeurs locales `127.0.0.1`, `petgarden`, `root` et un mot de passe vide si les variables ne sont pas définies. Ne réutilisez pas ces valeurs par défaut en production.
4. Démarrer un serveur PHP à la racine :

   ```bash
   php -S localhost:8000
   ```

5. Ouvrir <http://localhost:8000>.

> **À savoir :** l’application propose maintenant une première liste d’annonces, une inscription et une connexion. Ces parcours nécessitent une base MySQL importée et n’ont pas encore été validés contre une instance réelle dans tous les environnements. Les comptes de démonstration du SQL utilisent les mots de passe de test historiques (`amine123`, `sarah123`, `lucas123`, `emma123`, `admin123`) ; ils sont réservés au développement local. Consultez [TODO.md](TODO.md) pour les vérifications restantes.

## Contribution

Consultez [CONTRIBUTING.md](CONTRIBUTING.md) pour le flux Git, les conventions de branches et les vérifications à effectuer avant une pull request.

## Historique des versions

Les changements notables sont consignés dans [CHANGELOG.md](CHANGELOG.md).

## Équipe

- Rayan
- Saobane
- Predipe
