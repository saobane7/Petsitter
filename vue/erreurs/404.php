<?php
$titre_page = 'Page introuvable';
$page_active = '';
require __DIR__ . '/../partials/entete.php';
?>

<main class="conteneur page-erreur">
    <section class="carte carte-erreur">
        <div class="erreur-illustration">
            <?= patte(56) ?>
            <span class="erreur-code">404</span>
        </div>

        <p class="surtitre">Erreur 404</p>
        <h1>Page introuvable</h1>
        <p class="texte-doux">
            Désolé, la page que vous recherchez n’existe pas ou a été déplacée.
        </p>

        <?php if (!empty($message_erreur)) : ?>
            <p class="erreur-detail"><?= e(majuscule($message_erreur)) ?></p>
        <?php endif; ?>

        <div class="erreur-boutons">
            <a href="index.php" class="bouton bouton-plein">Retour à l’accueil</a>
            <a href="annonces.php" class="bouton bouton-contour">Voir les annonces</a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../partials/pied.php'; ?>