# Vérification de la checklist Petgarden

État vérifié à partir des fichiers présents dans le dépôt. Une coche signifie que le point est visible dans le code ; elle ne certifie pas un élément qui nécessite un accès à la machine ou au service concerné.

## Checklist initiale

- [ ] **Base de données créée avec ses 4 tables — partiel.** `sql/petgarden.sql` définit bien `utilisateur`, `annonces`, `candidatures` et `avis`. La création réelle dans MySQL n'est pas vérifiable depuis le dépôt. En outre, l'insertion des utilisateurs renseigne `password`, alors que la colonne créée s'appelle `mdp` ; le script échouera à cette étape.
- [ ] **Connexion de la base sans erreur — non.** `config/ConnexionBDD.php` contient une erreur de syntaxe (point-virgule manquant avant `try`) et d'autres erreurs visibles dans la configuration et le `catch`. La vérification `php -l` échoue sur ce fichier.
- [ ] **Le routeur affiche au moins une page (liste des annonces) — non.** Il n'y a pas de routeur dans les fichiers présents. `index.php` affiche une page de test et ne lit pas les annonces.
- [ ] **Inscription et connexion fonctionnelles — non.** `FormulaireConnexion/Connexion.class.php` interroge une table `user` inexistante au lieu de `utilisateur`, appelle `array()` au lieu de `execute()`, ne récupère pas le mot de passe et ne vérifie aucune authentification. Aucune inscription ni vue de formulaire n'est présente.
- [ ] **Chaque étudiant sait précisément sur quels fichiers il travaille — à confirmer avec l'équipe.** Le dépôt ne documente pas la répartition des fichiers entre Rayan, Saobane et Predipe.
- [ ] **Dépôt Git et trois branches — partiel.** Le dépôt Git existe. Seule la branche `main` apparaît en local, avec `origin/main` côté distant ; les trois branches annoncées ne sont pas présentes dans les références visibles.

## Manquements à traiter

- [ ] Corriger les erreurs de syntaxe et de configuration dans `config/ConnexionBDD.php`, puis vérifier la connexion à MySQL.
- [ ] Remplacer `password` par `mdp` dans les insertions SQL et utiliser des mots de passe hachés adaptés à `password_verify()`.
- [ ] Créer le routeur et une page qui liste les annonces ouvertes depuis la base.
- [ ] Implémenter les formulaires et traitements d'inscription et de connexion avec requêtes préparées, hachage des mots de passe et session.
- [ ] Aligner les requêtes et noms de tables sur le schéma SQL (`utilisateur`, etc.).
- [ ] Créer/nommer les branches de travail convenues et documenter la répartition des fichiers entre les trois membres.
- [ ] Corriger dans `modele/utilisateur.class.php` `getPrenom()` (renvoie actuellement le nom) et retirer les paramètres superflus des getters `getEmail()`, `getMdp()` et `getRole()`.
