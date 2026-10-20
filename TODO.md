# Vérification de la checklist Petgarden

État vérifié à partir des fichiers présents dans le dépôt. Une coche signifie que le point est visible dans le code ; elle ne certifie pas un élément qui nécessite un accès à la machine ou au service concerné.

## Checklist initiale

- [x] **Le schéma SQL définit les 4 tables et les données de test correspondent aux colonnes.** `sql/petgarden.sql` définit `utilisateur`, `annonces`, `candidatures` et `avis`; les mots de passe de démonstration sont hachés dans `mdp`. L'import réel dans MySQL reste à faire/vérifier sur chaque poste.
- [ ] **La base de données se connecte sans erreur — code corrigé, connexion réelle à confirmer.** `config/ConnexionBDD.php` utilise PDO, `utf8mb4`, les requêtes natives et des variables d'environnement. Il faut encore la lancer avec un serveur MySQL configuré.
- [x] **Le routeur affiche une liste d'annonces ouvertes.** `index.php` gère les pages `annonces`, `connexion`, `inscription` et `deconnexion`; les annonces viennent de `modele/Annonce.class.php`.
- [ ] **Inscription et connexion — implémentées, intégration base à confirmer.** Les traitements utilisent la table `utilisateur`, des requêtes préparées, `password_hash()` / `password_verify()`, une session renouvelée après authentification et un jeton CSRF.
- [ ] **Chaque étudiant sait précisément sur quels fichiers il travaille — à convenir en équipe.** La répartition entre Rayan, Saobane et Predipe n'est pas connue dans le dépôt.
- [ ] **Dépôt Git et trois branches — à organiser.** Le dépôt existe ; une seule branche de travail est actuellement connue dans cette copie. Convenez ensemble de la stratégie et créez/poussez les branches nécessaires.

## Vérifications restantes

- [ ] Importer `sql/petgarden.sql` dans une base locale vierge et vérifier les quatre tables et les données de test.
- [ ] Tester la connexion PDO, la liste d'annonces, l'inscription, la connexion avec mot de passe correct/incorrect et la déconnexion contre MySQL.
- [ ] Confirmer la répartition des fichiers entre Rayan, Saobane et Predipe et consigner les responsabilités convenues.
- [ ] Convenez des branches d'équipe puis créez-les/poussez-les ; ne pas inventer de noms sans accord commun.
- [ ] Ajouter les parcours de candidatures, gestion d'annonces et avis, qui ne font pas partie de cette première tranche.
