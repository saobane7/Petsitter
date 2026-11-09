<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces de garde - Petgarden</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header>
        <a href="annonces.php"><strong>Petgarden</strong></a>
        <nav>
            <a href="accueil.php">Mon espace</a>
            <a href="mesAnnonces.php">Mes annonces</a>
            <a href="creerAnnonce.php">Publier une annonce</a>
            <a href="Connexion.php">Connexion</a>
            <a href="Inscription.php">Inscription</a>
        </nav>
    </header>

    <main>
        <h1>Annonces de garde</h1>

        <?php if (empty($annonces)) : ?>
            <p>Aucune annonce ouverte pour le moment.</p>
        <?php endif; ?>

        <?php foreach ($annonces as $annonce) : ?>
            <article class="carte">
                <h2><?= htmlspecialchars($annonce['titre'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="meta">
                    <?= htmlspecialchars($annonce['type_animal'], ENT_QUOTES, 'UTF-8') ?>
                    ·
                    <?= htmlspecialchars($annonce['ville'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p>
                    Du <?= htmlspecialchars($annonce['date_debut'], ENT_QUOTES, 'UTF-8') ?>
                    au <?= htmlspecialchars($annonce['date_fin'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <?php if (!empty($annonce['description'])) : ?>
                    <p><?= nl2br(htmlspecialchars($annonce['description'], ENT_QUOTES, 'UTF-8')) ?></p>
                <?php endif; ?>
                <p class="meta">
                    Publié par
                    <?= htmlspecialchars($annonce['prenom'] . ' ' . $annonce['nom'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            </article>
        <?php endforeach; ?>
    </main>

</body>
</html>
