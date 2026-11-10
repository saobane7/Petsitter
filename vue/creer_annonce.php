<?php
$titre_page = 'Publier une annonce';
$page_active = 'mes_annonces';
require __DIR__ . '/partials/entete.php';

$action_formulaire = 'creer_annonce.php';
$libelle_bouton = 'Publier l’annonce';
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre">Espace propriétaire</p>
        <h1>Publier une annonce</h1>
        <p class="texte-doux">Décrivez votre besoin : les gardiens pourront postuler.</p>
    </div>

    <?php require __DIR__ . '/partials/formulaire_annonce.php'; ?>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
