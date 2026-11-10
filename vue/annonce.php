<?php
require_once __DIR__ . '/partials/fonctions.php';
$titre_page = majuscule($annonce['titre']);
$page_active = 'annonces';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page">
    <a href="annonces.php" class="retour">← Retour aux annonces</a>

    <div class="mise-en-page mise-en-page-detail">
        <section class="carte detail">
            <span class="statut statut-badge statut-<?= e($annonce['statut']) ?>"><?= e(libelle_statut($annonce['statut'])) ?></span>
            <h1><?= e(majuscule($annonce['titre'])) ?></h1>
            <p class="texte-doux"><?= e(majuscule($annonce['type_animal'])) ?> · <?= e(titre($annonce['ville'])) ?></p>

            <div class="infos-encadrees">
                <div><small>Dates</small><strong><?= e(periode($annonce['date_debut'], $annonce['date_fin'], true)) ?></strong></div>
                <div><small>Type d’animal</small><strong><?= e(majuscule($annonce['type_animal'])) ?></strong></div>
                <div><small>Ville</small><strong><?= e(titre($annonce['ville'])) ?></strong></div>
            </div>

            <h2 class="titre-section-petit">Description des besoins</h2>
            <p><?= !empty($annonce['description']) ? nl2br(e(majuscule($annonce['description']))) : 'Le propriétaire n’a pas ajouté de description.' ?></p>

            <div class="detail-pied">
                <div class="personne">
                    <span class="avatar avatar-l"><?= e(initiales($annonce['prenom'], $annonce['nom'])) ?></span>
                    <div>
                        <strong>Publiée par <?= e(nom_complet($annonce['prenom'], $annonce['nom'])) ?></strong>
                        <?php if ((int) $reputation['nb_avis'] > 0) : ?>
                            <span class="note-ligne">
                                <?= etoiles($reputation['note_moyenne']) ?>
                                <strong><?= e(note($reputation['note_moyenne'])) ?> / 5 · <?= e(pluriel($reputation['nb_avis'], 'avis')) ?></strong>
                            </span>
                        <?php else : ?>
                            <small>Pas encore d’avis</small>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="profil.php?id=<?= (int) $annonce['id_proprietaire'] ?>" class="bouton bouton-contour">Voir le profil</a>
            </div>
        </section>

        <aside class="carte carte-sombre postuler">
            <?php if ($connecte === null) : ?>
                <h2>Postuler à cette annonce</h2>
                <p>Connectez-vous pour présenter votre candidature au propriétaire.</p>
                <a href="connexion.php" class="bouton bouton-clair bouton-large">Se connecter</a>
                <small>Pas encore de compte ? <a href="inscription.php">Créer un compte</a></small>

            <?php elseif ($estProprietaire) : ?>
                <h2>C’est votre annonce</h2>
                <p>Consultez les candidatures reçues et validez le gardien de votre choix.</p>
                <a href="candidatures.php?id=<?= (int) $annonce['id_annonce'] ?>" class="bouton bouton-clair bouton-large">Voir les candidatures</a>

            <?php elseif ($maCandidature !== false) : ?>
                <h2>Candidature envoyée</h2>
                <p>Envoyée le <?= e(jour_mois($maCandidature['date_candidature'])) ?>.</p>
                <span class="statut statut-clair statut-<?= e(classe_statut($maCandidature['statut'])) ?>"><?= e(libelle_statut($maCandidature['statut'])) ?></span>
                <a href="mes_candidatures.php" class="bouton bouton-clair bouton-large">Mes candidatures</a>

            <?php elseif ($annonce['statut'] !== 'ouverte') : ?>
                <h2>Annonce <?= e(mb_strtolower(libelle_statut($annonce['statut']))) ?></h2>
                <p>Cette annonce n’accepte plus de candidatures.</p>
                <a href="annonces.php" class="bouton bouton-clair bouton-large">Voir les annonces ouvertes</a>

            <?php else : ?>
                <h2>Postuler à cette annonce</h2>
                <p>Présentez-vous au propriétaire : expérience, disponibilités, questions.</p>

                <?php require __DIR__ . '/partials/erreurs.php'; ?>

                <form method="post" action="annonce.php?id=<?= (int) $annonce['id_annonce'] ?>" class="formulaire">
                    <?php require __DIR__ . '/partials/csrf.php'; ?>
                    <label for="message">Votre message de motivation</label>
                    <textarea id="message" name="message" rows="6" maxlength="2000" required placeholder="Bonjour, je suis disponible du <?= e(periode($annonce['date_debut'], $annonce['date_fin'])) ?>…"><?= e($message) ?></textarea>
                    <button type="submit" class="bouton bouton-clair bouton-large">Envoyer ma candidature</button>
                </form>
                <small>Une seule candidature par annonce. Vous suivez sa réponse dans « Mes candidatures ».</small>
            <?php endif; ?>
        </aside>
    </div>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
