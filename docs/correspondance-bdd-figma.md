# Correspondance entre la base de données et la maquette

Ce document compare `database/petgarden.sql` (base de Saobane) et la maquette Figma (Predipe).

## 1. Éléments du Figma ajoutés à la base

| Écran Figma | Ajout dans la base |
| --- | --- |
| Mon compte > Profil | `utilisateur` : `telephone`, `ville`, `code_postal`, `a_propos`, `photo` |
| Mon compte > Mon utilisation | `utilisateur` : `est_client`, `est_petsitter`, `notifications_email` |
| Accueil > PetSitters près de chez vous | `utilisateur` : `presentation_petsitter`, `profil_verifie`, `latitude`, `longitude` (pour la distance en km) |
| Espace PetSitter > « Disponible pour de nouvelles gardes » | `utilisateur.disponible` |
| Accueil > « Votre animal » | tables `espece`, `petsitter_espece` |
| Accueil > « Service » et légende du planning | tables `service`, `petsitter_service` (tarif en euros par visite ou par jour) |
| Mes annonces > « Chien · Cocker », Mon compte > Mes animaux | table `animal`, liée à `annonces.id_animal` |
| Mes annonces > « Brouillon », « Dates à définir » | `annonces.statut` accepte `brouillon`, `date_debut` et `date_fin` peuvent être vides pour un brouillon |
| Mes annonces > « Chaque jour, 12h30 » | `annonces.horaire`, `annonces.id_service` |
| Mes annonces > « PetSitter : Thomas B. » | déduit de la candidature acceptée (aucune colonne ajoutée) |
| Accueil > « Réserver », Espace PetSitter > Demandes en attente, « Réservation confirmée » | table `reservation` |
| Espace PetSitter > planning de la semaine | table `creneau` (heure vide = « Journée ») |
| Messagerie | tables `conversation` et `message` (type `reservation` pour la carte « Réservation confirmée ») |
| Mon compte > Paiement et factures (onglet sans contenu) | tables `facture` et `paiement` |

Les données de démonstration reproduisent les écrans de la maquette (compte de Léa Marchand, planning du 2 au 8 novembre 2026, 5 annonces, 4 conversations).

## 2. Éléments de la base absents du Figma

Ils sont dessinés dans `docs/maquette/petgarden-elements-bdd-absents.pdf` et `docs/maquette/figma-import/`.

| Élément de la base | Écran proposé |
| --- | --- |
| `utilisateur.email`, `mdp` | 01 Connexion |
| `utilisateur.nom`, `prenom`, `est_client`, `est_petsitter` | 02 Inscription |
| `annonces.description`, brouillon, animal, service | 03 Publier une annonce |
| `annonces` ouvertes | 04 Annonces ouvertes |
| `candidatures.message` | 05 Détail d'une annonce et candidature |
| `candidatures.statut`, `date_candidature` | 06 Candidatures reçues |
| `avis` | 07 Laisser un avis |
| `animal` | 08 Mes animaux |
| `facture`, `paiement` | 09 Paiement et factures |
| `utilisateur.role`, `profil_verifie` | 10 Administration |

## 3. Choix à valider avec l'équipe

- **Suppression de compte.** Le Figma annonce que les annonces, messages et réservations sont effacés : les clés étrangères de ces tables passent donc en `on delete cascade`. Les `avis` restent en `restrict` (contrainte `chk_auteur_cible` conservée) : le code doit les supprimer avant le compte.
- **Factures et paiements.** Le Figma n'a que le titre de l'onglet : les colonnes de `facture` et `paiement` sont une proposition.
- **Deux textes de présentation.** Léa a un texte « À propos de moi » (Mon compte) et un autre sur sa carte PetSitter : deux colonnes, `a_propos` et `presentation_petsitter`.
- **Distance.** `latitude` et `longitude` sont vides dans les données de démonstration : la distance en km se calcule à partir d'elles.
- **Contrôle propriétaire ≠ gardien.** Non vérifié par la base (limite de MySQL sur les contraintes `check` avec `cascade`) : à contrôler dans le code.
