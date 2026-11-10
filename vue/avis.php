<?php
$titre_page = 'Laisser un avis';
$page_active = $roleCible === 'Gardien' ? 'mes_annonces' : 'mes_candidatures';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre"><?= $roleCible === 'Gardien' ? 'Espace propriétaire' : 'Espace gardien' ?></p>
        <h1>Laisser un avis</h1>
        <p class="texte-doux">Disponible une fois la garde terminée.</p>
    </div>

    <div class="mise-en-page mise-en-page-avis">
        <section class="carte carte-formulaire">
            <div class="encart-garde">
                <span class="avatar"><?= e(initiales($cible['prenom'], $cible['nom'])) ?></span>
                <div>
                    <strong><?= e(majuscule($annonce['titre'])) ?></strong>
                    <small><?= e(periode($annonce['date_debut'], $annonce['date_fin'])) ?> · <?= e($roleCible) ?> : <?= e(nom_complet($cible['prenom'], $cible['nom'])) ?></small>
                </div>
                <span class="statut statut-clair statut-<?= e($annonce['statut']) ?>"><?= e(libelle_statut($annonce['statut'])) ?></span>
            </div>

            <h2 class="titre-section-petit">Comment s’est passée la garde ?</h2>

            <?php require __DIR__ . '/partials/erreurs.php'; ?>

            <?php if ($dejaLaisse) : ?>
                <p class="alerte alerte-info">Vous avez déjà laissé un avis pour cette garde. Merci !</p>
            <?php elseif ($annonce['statut'] !== 'terminee') : ?>
                <p class="alerte alerte-info">Vous pourrez noter <?= e(titre($cible['prenom'])) ?> une fois la garde clôturée par le propriétaire.</p>
            <?php else : ?>
                <form method="post" action="avis.php?id=<?= (int) $annonce['id_annonce'] ?>" class="formulaire">
                    <?php require __DIR__ . '/partials/csrf.php'; ?>

                    <fieldset class="choix-note">
                        <legend>Votre note</legend>
                        <div class="etoiles-saisie">
                            <?php for ($i = 5; $i >= 1; $i--) : ?>
                                <input type="radio" id="note<?= $i ?>" name="note" value="<?= $i ?>" <?= $note === $i ? 'checked' : '' ?> required>
                                <label for="note<?= $i ?>" title="<?= $i ?> / 5">★</label>
                            <?php endfor; ?>
                        </div>
                        <span class="note-choisie" data-note-choisie><?= $note > 0 ? $note : '–' ?> / 5</span>
                    </fieldset>

                    <label for="commentaire">Votre commentaire</label>
                    <textarea id="commentaire" name="commentaire" rows="5" maxlength="150" placeholder="<?= e(titre($cible['prenom'])) ?> a été très attentionné…" data-compteur="compteur-commentaire"><?= e($commentaire) ?></textarea>
                    <small class="compteur-caracteres" id="compteur-commentaire"><?= mb_strlen($commentaire) ?> / 150</small>

                    <div class="boutons">
                        <button type="submit" class="bouton bouton-plein">Publier mon avis</button>
                        <small class="texte-doux">Un seul avis par garde.</small>
                    </div>
                </form>
            <?php endif; ?>
        </section>

        <aside class="carte reputation">
            <h2 class="titre-carte">Votre réputation</h2>
            <?php if ((int) $reputation['nb_avis'] > 0) : ?>
                <div class="reputation-note">
                    <strong><?= e(note($reputation['note_moyenne'])) ?></strong>
                    <div>
                        <?= etoiles($reputation['note_moyenne'], 'etoiles-l') ?>
                        <small><?= e(pluriel($reputation['nb_avis'], 'avis')) ?> <?= (int) $reputation['nb_avis'] > 1 ? 'reçus' : 'reçu' ?></small>
                    </div>
                </div>
            <?php else : ?>
                <p class="texte-doux">Vous n’avez pas encore reçu d’avis.</p>
            <?php endif; ?>
            <p class="note-bas">Moyenne de toutes les notes reçues, affichée sur votre profil.</p>

            <?php foreach ($avisRecus as $avis) : ?>
                <div class="avis-ligne">
                    <div class="avis-haut">
                        <span class="avatar"><?= e(initiales($avis['prenom'], $avis['nom'])) ?></span>
                        <div>
                            <strong><?= e(nom_court($avis['prenom'], $avis['nom'])) ?></strong>
                            <small><?= e(jour_mois($avis['date_avis'])) ?></small>
                        </div>
                        <?= etoiles($avis['note']) ?>
                    </div>
                    <?php if (!empty($avis['commentaire'])) : ?>
                        <p><?= e(majuscule($avis['commentaire'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </aside>
    </div>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
