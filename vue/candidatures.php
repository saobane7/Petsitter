<?php
$titre_page = 'Candidatures reçues';
$page_active = 'mes_annonces';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre">Espace propriétaire</p>
        <h1>Candidatures reçues</h1>
        <p class="texte-doux"><?= e(majuscule($annonce['titre'])) ?> · <?= e(periode($annonce['date_debut'], $annonce['date_fin'])) ?></p>
    </div>

    <div class="mise-en-page">
        <div class="liste">
            <?php if (empty($candidatures)) : ?>
                <p class="carte vide">Aucune candidature pour le moment. Les gardiens intéressés apparaîtront ici.</p>
            <?php endif; ?>

            <?php foreach ($candidatures as $candidature) : ?>
                <article class="carte candidature">
                    <div class="candidature-haut">
                        <div class="personne">
                            <span class="avatar avatar-l"><?= e(initiales($candidature['prenom'], $candidature['nom'])) ?></span>
                            <div>
                                <strong class="nom"><?= e(nom_complet($candidature['prenom'], $candidature['nom'])) ?></strong>
                                <?php if ((int) $candidature['nb_avis'] > 0) : ?>
                                    <span class="note-ligne">
                                        <?= etoiles($candidature['note_moyenne']) ?>
                                        <strong><?= e(note($candidature['note_moyenne'])) ?> / 5 · <?= e(pluriel($candidature['nb_avis'], 'avis')) ?></strong>
                                    </span>
                                <?php else : ?>
                                    <small>Pas encore d’avis</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="candidature-statut">
                            <span class="statut statut-clair statut-<?= e(classe_statut($candidature['statut'])) ?>"><?= e(libelle_statut($candidature['statut'])) ?></span>
                            <small>Reçue le <?= e(jour_mois($candidature['date_candidature'])) ?></small>
                        </div>
                    </div>

                    <p class="message"><?= nl2br(e(majuscule($candidature['message']))) ?></p>

                    <div class="actions">
                        <?php if ($annonce['statut'] === 'ouverte' && $candidature['statut'] === 'en attente') : ?>
                            <form method="post" action="candidatures.php?id=<?= (int) $annonce['id_annonce'] ?>">
                                <?php require __DIR__ . '/partials/csrf.php'; ?>
                                <input type="hidden" name="id_candidature" value="<?= (int) $candidature['id_candidature'] ?>">
                                <button type="submit" class="bouton bouton-plein bouton-petit">Valider ce gardien</button>
                            </form>
                        <?php endif; ?>
                        <a href="profil.php?id=<?= (int) $candidature['id_gardien'] ?>" class="lien-fort">Voir le profil</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <aside class="colonne-laterale">
            <section class="carte resume">
                <h2 class="titre-carte">Résumé</h2>
                <p class="resume-total"><strong><?= (int) $total ?></strong> <?= $total > 1 ? 'candidatures' : 'candidature' ?></p>
                <ul class="resume-lignes">
                    <li class="point-en-attente">En attente <strong><?= (int) $compteurs['en attente'] ?></strong></li>
                    <li class="point-acceptee">Acceptée <strong><?= (int) $compteurs['acceptee'] ?></strong></li>
                    <li class="point-refusee">Refusée <strong><?= (int) $compteurs['refusee'] ?></strong></li>
                </ul>
                <p class="note-bas">Valider un gardien passe l’annonce en « Pourvue » et refuse automatiquement les autres candidatures.</p>
                <?php if ($annonce['statut'] === 'ouverte') : ?>
                    <a href="modifier_annonce.php?id=<?= (int) $annonce['id_annonce'] ?>" class="bouton bouton-contour bouton-large">Modifier l’annonce</a>
                <?php endif; ?>
            </section>
        </aside>
    </div>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
