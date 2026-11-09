
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon espace - PetProject</title>
</head>
<body>

    <h1>Bienvenue sur PetProject</h1>

    <h2>
        Bonjour <?= htmlspecialchars($utilisateur['prenom'], ENT_QUOTES, 'UTF-8') ?> !
    </h2>

    <h3>Mes informations personnelles</h3>

    <p>
        Nom :
        <?= htmlspecialchars($utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        Prénom :
        <?= htmlspecialchars($utilisateur['prenom'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        Adresse e-mail :
        <?= htmlspecialchars($utilisateur['email'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        Rôle :
        <?= htmlspecialchars($utilisateur['role'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <a href="deconnexion.php">Se déconnecter</a>

</body>
</html>
