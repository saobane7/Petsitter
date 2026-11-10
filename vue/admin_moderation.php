<?php
$titre_page = 'Modération des avis';
$page_active = 'moderation';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre">Administration</p>
        <h1>Modération des avis</h1>
        <p class="texte-doux">Supprimez les commentaires abusifs et suivez l’activité de la plateforme.</p>
    </div>

    <div class="grille grille-4">
        <div class="carte stat-admin">
            <strong><?= (int) $gardesRealisees ?></strong>
            <span>Gardes réalisées</span>
        </div>
        <div class="carte stat-admin">
            <strong><?= $statistiquesAvis['note_moyenne'] !== null ? e(note($statistiquesAvis['note_moyenne'])) . ' / 5' : '—' ?></strong>
            <span>Note moyenne de la plateforme</span>
        </div>
        <div class="carte stat-admin">
            <strong><?= (int) $statistiquesAvis['nb_avis'] ?></strong>
            <span>Avis publiés</span>
        </div>
        <div class="carte stat-admin">
            <strong><?= (int) $nbComptes ?></strong>
            <span>Comptes</span>
        </div>
    </div>

    <section class="carte tableau-carte">
        <h2 class="titre-carte">Avis récents</h2>

        <?php if (empty($avisRecents)) : ?>
            <p class="texte-doux">Aucun avis publié.</p>
        <?php else : ?>
            <div class="tableau-defilant">
                <table class="tableau">
                    <thead>
                        <tr>
                            <th>Auteur → cible</th>
                            <th>Annonce</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($avisRecents as $avis) : ?>
                            <tr>
                                <td><strong><?= e(nom_court($avis['prenom_auteur'], $avis['nom_auteur'])) ?> → <?= e(nom_court($avis['prenom_cible'], $avis['nom_cible'])) ?></strong></td>
                                <td class="texte-doux tronque"><?= e(majuscule($avis['titre'])) ?></td>
                                <td><?= etoiles($avis['note']) ?></td>
                                <td><?= e(majuscule($avis['commentaire'] ?? '—')) ?></td>
                                <td class="texte-doux"><?= e(jour_mois_court($avis['date_avis'])) ?></td>
                                <td>
                                    <form method="post" action="admin_moderation.php" data-confirmer="Supprimer cet avis ?">
                                        <?php require __DIR__ . '/partials/csrf.php'; ?>
                                        <input type="hidden" name="id_avis" value="<?= (int) $avis['id_avis'] ?>">
                                        <button type="submit" class="bouton bouton-contour bouton-petit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
