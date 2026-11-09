<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes annonces</title>
</head>
<body>

    <h1>Mes annonces</h1>

    <?php if (empty($annonces)): ?>

        <p>Vous n'avez publié aucune annonce pour le moment.</p>

    <?php else: ?>

        <?php foreach ($annonces as $annonce): ?>

    <div>
        <h2><?= htmlspecialchars($annonce['titre']) ?></h2>

        <p>Animal : <?= htmlspecialchars($annonce['type_animal']) ?></p>

        <p>Ville : <?= htmlspecialchars($annonce['ville']) ?></p>

        <p>Date de début : <?= htmlspecialchars($annonce['date_debut'] ?? '') ?></p>

        <p>Date de fin : <?= htmlspecialchars($annonce['date_fin'] ?? '') ?></p>

        <p>Description : <?= htmlspecialchars($annonce['description'] ?? '') ?></p>

        <p>Statut : <?= htmlspecialchars($annonce['statut']) ?></p>
    </div>
    <a href="modifierAnnonce.php?id=<?= $annonce['id'] ?>">
    Modifier cette annonce
    </a>

    <hr>

<?php endforeach; ?>

    <?php endif; ?>

</body>
</html>