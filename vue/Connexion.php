
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - PetProject</title>
</head>
<body>

    <h1>Connexion à PetProject</h1>

    <?php if (!empty($erreurs)) : ?>
        <ul>
            <?php foreach ($erreurs as $erreur) : ?>
                <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">

        <label for="email">Adresse e-mail :</label>
        <input
            type="email"
            id="email"
            name="email"
            required
        >

        <br><br>

        <label for="mdp">Mot de passe :</label>
        <input
            type="password"
            id="mdp"
            name="mdp"
            required
        >

        <br><br>

        <button type="submit">Se connecter</button>

    </form>

</body>
</html>
