# Checklist du projet

Liste de suivi des tâches de l'équipe. Pour ajouter une tâche, créer une ligne `- [ ] description` dans la section voulue ; pour la cocher, remplacer l'espace entre crochets par un `x` : `- [x] description`.

Une tâche terminée reste visible dans « Terminé » (la déplacer à la main) et, si elle change le comportement visible du site, est consignée dans [`CHANGELOG.md`](CHANGELOG.md).

## À faire

- [ ] 
- [ ] Rétablir la protection CSRF des formulaires (perdue lors de la fusion)
- [ ] Valider avec l'équipe les écrans complémentaires et les tables `facture` et `paiement`

## En cours

## Terminé

- [x] Architecture MVC (`model/`, `vue/`, `controleur/`, `public/`)
- [x] Schéma SQL : tables `utilisateur`, `annonces`, `candidatures`, `avis`
- [x] Liste publique des annonces ouvertes
- [x] Inscription, connexion et déconnexion (mots de passe hachés)
- [x] Publication, liste et modification de ses annonces
- [x] Base de données enrichie avec les éléments de la maquette
- [x] Écrans complémentaires pour les éléments de la base absents du Figma
