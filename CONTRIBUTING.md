# Contribuer à Petgarden

Ce dépôt est développé en équipe par Rayan, Saobane et Predipe. Utilisez une branche par tâche afin que les changements soient faciles à relire et à intégrer.

## Prendre une tâche

1. Consultez `TODO.md` et convenez dans l’équipe de la personne responsable.
2. Vérifiez que le périmètre et les fichiers concernés sont clairs.
3. Créez une branche depuis `main` :

   ```bash
   git switch main
   git pull --ff-only
   git switch -c type/sujet-court
   ```

Préfixes suggérés : `feat/`, `fix/`, `docs/`, `refactor/`.

## Faire un changement

- Gardez chaque branche centrée sur une seule tâche.
- N’ajoutez jamais de mot de passe, clé ou donnée personnelle au dépôt. Les paramètres locaux ne doivent pas être commités.
- Utilisez des requêtes préparées pour les entrées utilisateur et `password_hash()` / `password_verify()` pour les mots de passe.
- Mettez à jour `README.md`, `TODO.md` ou `CHANGELOG.md` quand le changement modifie l’installation, l’état des fonctionnalités ou le comportement visible.
- Écrivez les messages de commit en français ou en anglais, à l’impératif, et avec un préfixe cohérent, par exemple `fix: corrige le getter prenom` ou `docs: décrit l’installation locale`.

## Vérifier et ouvrir une pull request

Avant d’ouvrir une pull request :

1. Vérifiez les fichiers modifiés avec `git status` et retirez toute configuration ou donnée locale.
2. Lancez les vérifications adaptées au changement (au minimum `php -l` sur les fichiers PHP touchés et import du SQL dans une base de test lorsqu’il est modifié).
3. Comparez la branche avec `main`, puis poussez-la :

   ```bash
   git push -u origin nom-de-branche
   ```

4. Ouvrez une pull request vers `main`, décrivez le problème, le changement et les vérifications effectuées. Demandez une relecture à un autre membre de l’équipe.
5. Fusionnez après relecture et résolution des retours ; supprimez ensuite la branche terminée.

Ne poussez pas directement une fonctionnalité sur `main`. Pour un devoir ou une démonstration sans pull request, convenez explicitement d’une exception avec l’équipe.

## Versions et changelog

Ajoutez les changements destinés aux utilisateurs dans `CHANGELOG.md`, sous `Non publié`. Lorsqu’une version est livrée, déplacez ces entrées dans une section datée et créez un tag `vMAJEUR.MINEUR.CORRECTIF` (par exemple `v0.2.0`).
