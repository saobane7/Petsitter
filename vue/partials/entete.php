<?php
// en-tête commun : à inclure en haut de chaque vue après avoir défini $titre_page et $page_active
// $page_active : accueil, annonces, mes_annonces, mes_candidatures, moderation, comptes ou auth
require_once __DIR__ . '/fonctions.php';

$est_admin = $connecte !== null && $connecte['role'] === 'admin';

if ($est_admin) {
    $liens = ['moderation' => ['admin_moderation.php', 'Modération'], 'comptes' => ['admin_comptes.php', 'Comptes']];
} elseif ($connecte !== null) {
    $liens = [
        'accueil' => ['index.php', 'Accueil'],
        'annonces' => ['annonces.php', 'Annonces'],
        'mes_annonces' => ['mes_annonces.php', 'Mes annonces'],
        'mes_candidatures' => ['mes_candidatures.php', 'Mes candidatures'],
    ];
} else {
    $liens = ['accueil' => ['index.php', 'Accueil'], 'annonces' => ['annonces.php', 'Annonces']];
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titre_page) ?> · PetGarden</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:wght@600;700&display=swap">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/petgarden.js" defer></script>
</head>
<body>

<header class="entete">
    <div class="conteneur entete-contenu">
        <a class="logo" href="<?= $est_admin ? 'admin_moderation.php' : 'index.php' ?>">
            <img src="assets/img/logo.png" alt="" width="40" height="52">
            <span>PetGarden</span>
        </a>

        <?php if ($page_active !== 'auth') : ?>
            <nav class="navigation">
                <?php foreach ($liens as $cle => $lien) : ?>
                    <a href="<?= e($lien[0]) ?>" class="<?= $cle === $page_active ? 'actif' : '' ?>"><?= e($lien[1]) ?></a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <?php if ($connecte !== null) : ?>
            <details class="compte">
                <summary>
                    <span class="avatar avatar-xs"><?= e($est_admin ? 'AD' : initiales($connecte['prenom'], $connecte['nom'])) ?></span>
                    <?= e($est_admin ? 'Admin' : nom_court($connecte['prenom'], $connecte['nom'])) ?>
                </summary>
                <div class="compte-menu">
                    <?php if (!$est_admin) : ?>
                        <a href="profil.php">Mon profil</a>
                    <?php endif; ?>
                    <a href="deconnexion.php">Se déconnecter</a>
                </div>
            </details>
        <?php else : ?>
            <div class="entete-actions">
                <a href="connexion.php" class="lien-fort">Se connecter</a>
                <a href="inscription.php" class="bouton bouton-contour">Créer un compte</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<?php if (!empty($flash)) : ?>
    <div class="conteneur">
        <p class="alerte alerte-succes"><?= e($flash) ?></p>
    </div>
<?php endif; ?>
