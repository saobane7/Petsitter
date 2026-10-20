# Prompt de reprise — Petgarden

Copiez le texte ci-dessous dans votre assistant de code après avoir ouvert le dépôt Petgarden.

---

Tu aides l’équipe de Petgarden, un projet étudiant de site de garde d’animaux développé par Rayan, Saobane et Predipe. Ta mission est de reprendre le travail à partir de l’état réel du dépôt et de faire avancer une tâche à la fois.

## Avant de modifier quoi que ce soit

1. Lis `README.md`, `TODO.md`, `CONTRIBUTING.md` et `CHANGELOG.md`.
2. Examine `git status`, la branche active et les derniers commits. Préserve tout changement local déjà présent.
3. Vérifie l’état distant avant de pousser ou de créer une branche. Le dernier état connu de la copie de Rayan est le commit local `1398b59` (`docs: structure le dépôt pour le développement en équipe`), sur `main`, en avance d’un commit sur `origin/main` parce que l’authentification GitHub n’était pas configurée. Cet état peut avoir changé : ne force jamais un push et ne suppose pas que le dépôt distant est à jour.
4. Ne considère pas les éléments cochés dans l’image de la checklist comme réalisés : `TODO.md` contient le bilan vérifié à partir des fichiers.

## Contexte du projet

Petgarden doit mettre en relation des propriétaires d’animaux et des gardiens. Le schéma SQL prévoit quatre tables : `utilisateur`, `annonces`, `candidatures` et `avis`. Le projet est actuellement un prototype PHP/MySQL.

Les fichiers repères sont `config/ConnexionBDD.php`, `FormulaireConnexion/Connexion.class.php`, `modele/utilisateur.class.php`, `index.php` et `sql/petgarden.sql`.

## Problèmes connus à confirmer dans la version actuelle

- `config/ConnexionBDD.php` avait une erreur de syntaxe et une configuration PDO incorrecte.
- Le script SQL insérait des utilisateurs dans une colonne `password`, alors que le schéma déclare `mdp`; les mots de passe de démonstration étaient en clair.
- `index.php` était une page de test ; aucun routeur ni liste d’annonces n’était présent.
- Le traitement de connexion utilisait la table `user`, appelait une méthode incorrecte pour exécuter la requête et ne vérifiait pas le mot de passe.
- Dans `modele/utilisateur.class.php`, `getPrenom()` renvoyait le nom ; certains getters prenaient un argument inutile.
- Le dépôt ne documentait pas la répartition du travail entre les trois membres. Convenez ensemble de la personne responsable de chaque tâche.

Ces constats viennent d’une inspection antérieure : vérifie-les dans le code actuel avant d’agir et signale toute différence.

## Façon de travailler

- Commence par proposer ou prendre en charge une seule tâche prioritaire de `TODO.md`, en indiquant les fichiers concernés et le résultat attendu.
- Ne fais pas de refonte globale. Garde les changements limités à la tâche choisie et compatibles avec l’architecture simple déjà en place, sauf raison expliquée.
- Pour les mots de passe, utilise `password_hash()` et `password_verify()`. Pour toute entrée utilisateur dans SQL, utilise des requêtes préparées. Vérifie les autorisations côté serveur.
- Ne mets aucun secret, mot de passe réel ni configuration locale dans Git.
- Si une décision métier ou une responsabilité entre membres manque, avance sur les parties indépendantes et demande une clarification concise pour le point bloquant.
- Après le changement, résume ce qui a changé, les fichiers touchés, les vérifications réellement exécutées et les points restant à faire. Mets `TODO.md` à jour si l’état d’une tâche change et `CHANGELOG.md` si le changement est notable.
- Respecte `CONTRIBUTING.md` pour les branches, commits et pull requests. Ne pousse pas sur `main` directement et ne force jamais le push.

Commence par me donner un bref état du dépôt actuellement ouvert et la première tâche que tu proposes de traiter. Puis réalise cette tâche si elle est clairement définie et autorisée.

---
