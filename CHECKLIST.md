# Checklist du projet

Liste de suivi des tâches de l'équipe. Pour ajouter une tâche, créer une ligne `- [ ] description` dans la section voulue ; pour la cocher, remplacer l'espace entre crochets par un `x` : `- [x] description`.

Une tâche terminée reste visible dans « Terminé » (la déplacer à la main) et, si elle change le comportement visible du site, est consignée dans [`CHANGELOG.md`](CHANGELOG.md).

## À faire

### Fin de séance 2

- [ ] Pousser le code de chaque module sur sa branche `feature/<module>` (une seule branche `feature` existe pour l'instant)
- [ ] Chaque étudiant sait expliquer, en une phrase, ce que fait chaque méthode de son modèle
- [ ] Valider avec l'équipe les écrans complémentaires et les tables `facture` et `paiement` (la base actuelle n'a que 4 tables)

### Modération : suspendre un utilisateur

Objectif : empêcher la connexion d'un compte suspendu et le piloter depuis le back-office.

- [ ] Ajouter un champ `actif` (booléen, vrai par défaut) sur `Utilisateur`, puis générer la migration
- [ ] Créer `UserChecker` et le déclarer dans `security.yaml` (`user_checker` sur le firewall `main`)
- [ ] Ajouter la route `POST /admin/utilisateurs/{id}/suspendre` avec jeton CSRF
- [ ] Tester : un compte suspendu voit « Ce compte est suspendu » au moment de se connecter
- [ ] Discussion : que se passe-t-il pour un utilisateur déjà connecté au moment de la suspension ?

### Recherche, pagination et export

Objectif : assembler les trois fonctions sur la liste des annonces du back-office.

- [ ] Créer le formulaire GET de filtres (ville, statut, mot-clé) dans `admin/annonces.html.twig`
- [ ] Afficher la liste paginée avec les liens page précédente / suivante
- [ ] Ajouter un bouton « Exporter » qui conserve les filtres actifs dans l'URL
- [ ] Vérifier : l'export contient les mêmes annonces que la liste filtrée, toutes pages confondues
- [ ] Comparer avec le back-office PHP pur : combien de fichiers dupliquaient la logique de filtre ?

## En cours

## Terminé

### Fin de séance 1

- [x] La base de données est créée avec ses 4 tables (`utilisateur`, `annonces`, `candidatures`, `avis`)
- [x] La classe `Database` se connecte sans erreur
- [x] Le routeur affiche au moins une page (liste des annonces) : une page d'entrée par écran dans `public/`
- [x] L'authentification (inscription / connexion) fonctionne
- [x] Chaque étudiant sait précisément sur quel(s) fichier(s) il travaille
- [x] Le dépôt Git est créé et les 3 branches existent (`main`, `developpement`, `feature`)

### Fin de séance 2

- [x] Chaque module a une méthode de création qui valide les données avant d'écrire en base (inscription, annonce, candidature, avis)
- [x] Les listes s'affichent correctement même vides (annonces, mes annonces, candidatures, avis, profil)
- [x] Chaque action (créer, postuler, valider, clôturer, noter, supprimer) redirige avec un message flash
- [x] Les formulaires réaffichent les erreurs sans effacer la saisie (le mot de passe est volontairement vidé)
- [x] Les vues échappent toutes les données affichées avec `htmlspecialchars()` (via la fonction `e()`)
- [x] Les modules utilisent l'en-tête et le pied communs (`vue/partials/`) et les classes CSS partagées

### Fonctionnalités

- [x] Architecture MVC (`model/`, `vue/`, `controleur/`, `public/`)
- [x] Schéma SQL : tables `utilisateur`, `annonces`, `candidatures`, `avis` (corrigé pour s'importer sans erreur)
- [x] Liste publique des annonces ouvertes, filtrable par type d'animal et ville
- [x] Inscription, connexion et déconnexion (mots de passe hachés)
- [x] Publication, liste et modification de ses annonces
- [x] Candidatures : postuler, candidatures reçues, validation d'un gardien, mes candidatures
- [x] Clôture d'une garde et avis croisés propriétaire / gardien
- [x] Profil public : note moyenne, gardes réalisées, avis reçus
- [x] Administration : modération des avis, rôles et suppression des comptes
- [x] Protection CSRF de tous les formulaires
- [x] Vues et CSS calqués sur les 13 écrans de la maquette Figma
- [x] Noms de fichiers et SQL en minuscules
- [x] Écrans complémentaires pour les éléments de la base absents du Figma
