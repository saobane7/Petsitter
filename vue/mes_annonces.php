<?php
$titre_page = 'Mes annonces';
$page_active = 'mes_annonces';
require __DIR__ . '/partials/entete.php';

$onglets = ['' => ['Toutes', $total], 'ouverte' => ['Ouvertes', $compteurs['ouverte']],
            'pourvue' => ['Pourvues', $compteurs['pourvue']], 'terminee' => ['Terminées', $compteurs['terminee']]];
?>

<main class="conteneur page">
    <div class="page-titre page-titre-action">
        <div>
            <p class="surtitre">Espace propriétaire</p>
            <h1>Mes annonces</h1>
            <p class="texte-doux">Retrouvez toutes les annonces que vous avez publiées et suivez leurs candidatures.</p>
        </div>
        <a href="creer_annonce.php" class="bouton bouton-plein">+ Nouvelle annonce</a>
    </div>

    <div class="grille grille-3">
        <div class="carte stat">
            <strong><?= (int) $total ?></strong>
            <span><?= $total > 1 ? 'Annonces publiées' : 'Annonce publiée' ?></span>
        </div>
        <div class="carte stat">
            <strong><?= (int) $gardesConfiees ?></strong>
            <span><?= $gardesConfiees > 1 ? 'Gardes confiées' : 'Garde confiée' ?></span>
        </div>
        <div class="carte stat">
            <?php if ($gardienFavori) : ?>
                <span class="avatar"><?= e(initiales($gardienFavori['prenom'], $gardienFavori['nom'])) ?></span>
                <div>
                    <a class="lien-fort" href="profil.php?id=<?= (int) $gardienFavori['id_utilisateur'] ?>">
                        <?= e(nom_court($gardienFavori['prenom'], $gardienFavori['nom'])) ?> · <?= e(pluriel($gardienFavori['nb_gardes'], 'garde')) ?>
                    </a>
                    <small>Gardien le plus sollicité</small>
                </div>
            <?php else : ?>
                <div>
                    <strong class="stat-texte">Aucun gardien</strong>
                    <small>Validez un candidat pour le voir ici</small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <nav class="filtres-statut">
        <?php foreach ($onglets as $cle => $onglet) : ?>
            <a href="mes_annonces.php<?= $cle !== '' ? '?statut=' . e($cle) : '' ?>" class="<?= $statut === $cle ? 'actif' : '' ?>">
                <?= e($onglet[0]) ?> <span><?= (int) $onglet[1] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="grille grille-3">
        <?php foreach ($annonces as $annonce) : ?>
            <article class="carte carte-annonce">
                <div class="carte-image statut-<?= e($annonce['statut']) ?>">
                    <span class="statut statut-badge statut-<?= e($annonce['statut']) ?>"><?= e(libelle_statut($annonce['statut'])) ?></span>
                    <?= patte() ?>
                </div>
                <div class="carte-corps">
                    <h3><?= e(majuscule($annonce['titre'])) ?></h3>
                    <ul class="infos">
                        <li><?= e(majuscule($annonce['type_animal'])) ?></li>
                        <li><?= e(titre($annonce['ville'])) ?></li>
                        <li><?= e(periode($annonce['date_debut'], $annonce['date_fin'])) ?></li>
                    </ul>
                    <div class="carte-pied">
                        <?php if ($annonce['statut'] === 'ouverte') : ?>
                            <strong><?= e(pluriel($annonce['nb_candidatures'], 'candidature')) ?></strong>
                            <div class="actions">
                                <a href="modifier_annonce.php?id=<?= (int) $annonce['id_annonce'] ?>" class="lien-fort">Modifier</a>
                                <a href="candidatures.php?id=<?= (int) $annonce['id_annonce'] ?>" class="bouton bouton-contour bouton-petit">Voir</a>
                            </div>
                        <?php else : ?>
                            <strong>Gardien : <?= $annonce['id_gardien'] ? e(nom_court($annonce['prenom_gardien'], $annonce['nom_gardien'])) : '—' ?></strong>

                            <?php if ($annonce['statut'] === 'pourvue') : ?>
                                <form method="post" action="mes_annonces.php">
                                    <?php require __DIR__ . '/partials/csrf.php'; ?>
                                    <input type="hidden" name="id_annonce" value="<?= (int) $annonce['id_annonce'] ?>">
                                    <button type="submit" class="bouton bouton-contour bouton-petit">Clôturer la garde</button>
                                </form>
                            <?php elseif ($annonce['id_gardien'] && !$annonce['avis_laisse']) : ?>
                                <a href="avis.php?id=<?= (int) $annonce['id_annonce'] ?>" class="bouton bouton-plein bouton-petit">Laisser un avis</a>
                            <?php elseif ($annonce['avis_laisse']) : ?>
                                <span class="statut statut-clair statut-terminee">Avis publié</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <a href="creer_annonce.php" class="carte-ajout">
            <span>+</span>
            Publier une nouvelle annonce
        </a>
    </div>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
