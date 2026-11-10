<?php
$titre_page = 'Annonces de garde';
$page_active = 'annonces';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page">
    <div class="page-titre">
        <p class="surtitre">Espace gardien</p>
        <h1>Annonces de garde</h1>
        <p class="texte-doux">Trouvez une garde près de chez vous et postulez en quelques clics.</p>
    </div>

    <form method="get" action="annonces.php" class="carte filtres">
        <div>
            <label for="type_animal">Type d’animal</label>
            <select id="type_animal" name="type_animal">
                <option value="">Tous les animaux</option>
                <?php foreach ($typesAnimaux as $type) : ?>
                    <option value="<?= e($type) ?>" <?= $filtres['type_animal'] === $type ? 'selected' : '' ?>><?= e(majuscule($type)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="ville">Ville</label>
            <input type="text" id="ville" name="ville" value="<?= e($filtres['ville']) ?>" placeholder="Toutes les villes">
        </div>
        <button type="submit" class="bouton bouton-plein">Filtrer</button>
    </form>

    <p class="compteur"><?= e(pluriel(count($annonces), 'annonce') . ' ' . (count($annonces) > 1 ? 'ouvertes' : 'ouverte')) ?></p>

    <?php if (empty($annonces)) : ?>
        <p class="vide">Aucune annonce ne correspond à votre recherche.</p>
    <?php endif; ?>

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
                        <strong>Publiée par <?= e(nom_court($annonce['prenom'], $annonce['nom'])) ?></strong>
                        <a href="annonce.php?id=<?= (int) $annonce['id_annonce'] ?>" class="bouton bouton-plein bouton-petit">Voir l’annonce</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
