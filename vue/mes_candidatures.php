<?php
$titre_page = 'Mes candidatures';
$page_active = 'mes_candidatures';
require __DIR__ . '/partials/entete.php';

$onglets = ['' => ['Toutes', $total], 'en attente' => ['En attente', $compteurs['en attente']],
            'acceptee' => ['Acceptées', $compteurs['acceptee']], 'refusee' => ['Refusées', $compteurs['refusee']]];
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre">Espace gardien</p>
        <h1>Mes candidatures</h1>
        <p class="texte-doux">Suivez l’état de chacune de vos candidatures.</p>
    </div>

    <nav class="filtres-statut">
        <?php foreach ($onglets as $cle => $onglet) : ?>
            <a href="mes_candidatures.php<?= $cle !== '' ? '?statut=' . urlencode($cle) : '' ?>" class="<?= $statut === $cle ? 'actif' : '' ?>">
                <?= e($onglet[0]) ?> <span><?= (int) $onglet[1] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <?php if (empty($candidatures)) : ?>
        <p class="carte vide">Aucune candidature ici. <a href="annonces.php" class="lien-fort">Parcourir les annonces</a></p>
    <?php endif; ?>

    <div class="liste">
        <?php foreach ($candidatures as $candidature) : ?>
            <article class="carte ligne-candidature">
                <span class="vignette statut-<?= e(classe_statut($candidature['statut'])) ?>"><?= patte(34) ?></span>
                <div class="ligne-candidature-texte">
                    <h2><?= e(majuscule($candidature['titre'])) ?></h2>
                    <p class="texte-doux">
                        <?= e(majuscule($candidature['type_animal'])) ?> · <?= e(titre($candidature['ville'])) ?> · <?= e(periode($candidature['date_debut'], $candidature['date_fin'])) ?>
                    </p>
                    <strong>Publiée par <?= e(nom_court($candidature['prenom'], $candidature['nom'])) ?></strong>
                </div>
                <div class="ligne-candidature-actions">
                    <span class="statut statut-clair statut-<?= e(classe_statut($candidature['statut'])) ?>"><?= e(libelle_statut($candidature['statut'])) ?></span>
                    <small>Envoyée le <?= e(jour_mois($candidature['date_candidature'])) ?></small>
                    <?php if ($candidature['statut'] === 'acceptee' && $candidature['statut_annonce'] === 'terminee' && !$candidature['avis_laisse']) : ?>
                        <a href="avis.php?id=<?= (int) $candidature['id_annonce'] ?>" class="bouton bouton-plein bouton-petit">Laisser un avis</a>
                    <?php else : ?>
                        <a href="annonce.php?id=<?= (int) $candidature['id_annonce'] ?>" class="bouton bouton-contour bouton-petit">Voir l’annonce</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
