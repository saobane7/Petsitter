<?php
$titre_page = 'Modifier mon annonce';
$page_active = 'mes_annonces';
require __DIR__ . '/partials/entete.php';

$action_formulaire = 'modifier_annonce.php?id=' . (int) $annonce['id_annonce'];
$libelle_bouton = 'Enregistrer les modifications';
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre">Espace propriétaire</p>
        <h1>Modifier mon annonce</h1>
        <p class="texte-doux">Les gardiens qui ont déjà postulé verront la nouvelle version.</p>
    </div>

    <?php require __DIR__ . '/partials/formulaire_annonce.php'; ?>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
