<?php
require_once __DIR__ . '/partials/fonctions.php';
$titre_page = nom_complet($profil['prenom'], $profil['nom']);
$page_active = 'annonces';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page">
    <a href="annonces.php" class="retour">← Retour</a>

    <div class="mise-en-page mise-en-page-profil">
        <section class="carte profil">
            <span class="avatar avatar-xl"><?= e(initiales($profil['prenom'], $profil['nom'])) ?></span>
            <h1><?= e(nom_complet($profil['prenom'], $profil['nom'])) ?></h1>
            <p class="texte-doux"><?= $profil['role'] === 'admin' ? 'Administrateur' : 'Membre PetGarden' ?></p>

            <div class="profil-stats">
                <div>
                    <strong><?= $statistiques['note_moyenne'] !== null ? e(note($statistiques['note_moyenne'])) . ' / 5' : '—' ?></strong>
                    <span>Note moyenne</span>
                </div>
                <div>
                    <strong><?= (int) $statistiques['gardes_realisees'] ?></strong>
                    <span><?= (int) $statistiques['gardes_realisees'] > 1 ? 'Gardes réalisées' : 'Garde réalisée' ?></span>
                </div>
                <div>
                    <strong><?= (int) $statistiques['nb_avis'] ?></strong>
                    <span>Avis reçus</span>
                </div>
            </div>
        </section>

        <section class="carte">
            <h2 class="titre-carte titre-separe">Avis reçus</h2>

            <?php if (empty($avisRecus)) : ?>
                <p class="texte-doux">Aucun avis pour le moment.</p>
            <?php endif; ?>

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
                        <p class="avis-texte"><?= e(majuscule($avis['commentaire'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
