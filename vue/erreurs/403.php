<?php
$titre_page = 'Accès interdit';
$page_active = '';
require __DIR__ . '/../partials/entete.php';
?>

<main class="conteneur page-erreur">
    <section class="carte carte-erreur">
        <div class="erreur-illustration erreur-illustration-interdit">
            <?= patte(56) ?>
            <span class="erreur-code">403</span>
        </div>

        <p class="surtitre">Erreur 403</p>
        <h1>Accès interdit</h1>
        <p class="texte-doux">
            Désolé, vous n’avez pas l’autorisation d’accéder à cette page.
        </p>

        <?php if (!empty($message_erreur)) : ?>
            <p class="erreur-detail"><?= e(majuscule($message_erreur)) ?></p>
        <?php endif; ?>

        <div class="erreur-boutons">
            <a href="index.php" class="bouton bouton-plein">Retour à l’accueil</a>
            <?php if ($connecte === null) : ?>
                <a href="connexion.php" class="bouton bouton-contour">Se connecter</a>
            <?php else : ?>
                <a href="deconnexion.php" class="bouton bouton-contour">Changer de compte</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../partials/pied.php'; ?>