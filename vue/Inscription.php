
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - PetSitter</title>
</head>
<body>

    <h1>Créer un compte PetSitter</h1>
    <?php if (!empty($message)) : ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if (!empty($erreurs)) : ?>
    <ul>
        <?php foreach ($erreurs as $erreur) : ?>
            <li><?= htmlspecialchars($erreur) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

    <form method="POST">

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <br><br>

        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="mdp">Mot de passe :</label>
        <input type="password" id="mdp" name="mdp" required minlength="8">

        <br><br>

        <button type="submit">S'inscrire</button>

    </form>

</body>
</html>
