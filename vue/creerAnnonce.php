<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Publier une annonce</title>
</head>
<body>

    <h1>Publier une annonce</h1>

    <?php if (!empty($erreurs)): ?>

    <div>
        <?php foreach ($erreurs as $erreur): ?>
            <p><?= htmlspecialchars($erreur) ?></p>
        <?php endforeach; ?>
    </div>

<?php endif; ?>


<?php if (!empty($message)): ?>

    <p><?= htmlspecialchars($message) ?></p>

<?php endif; ?>

    <form action="creerAnnonce.php" method="POST">

    <label for="titre">Titre de l'annonce :</label>
    <input type="text" id="titre" name="titre" required>

    <label for="type_animal">Type d'animal :</label>
    <input type="text" id="type_animal" name="type_animal" required>

    <label for="ville">Ville :</label>
    <input type="text" id="ville" name="ville" required>

    <label for="date_debut">Date de début :</label>
    <input type="date" id="date_debut" name="date_debut" required>

    <label for="date_fin">Date de fin :</label>
    <input type="date" id="date_fin" name="date_fin" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description"></textarea>

    <button type="submit">Publier mon annonce</button>

</form>

</body>
</html>