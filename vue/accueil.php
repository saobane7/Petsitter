<?php
$titre_page = 'Accueil';
$page_active = 'accueil';
$avec_pied = true;
require __DIR__ . '/partials/entete.php';
?>

<section class="hero">
    <div class="conteneur hero-contenu">
        <div class="hero-texte">
            <span class="pastille-hero">Garde d’animaux de confiance</span>
            <h1>Vos compagnons méritent un jardin de bonnes mains.</h1>
            <p>PetGarden met en relation les propriétaires d’animaux et des habitants volontaires. Publiez une annonce, choisissez un gardien, puis notez-vous après la garde.</p>
            <ul class="hero-points">
                <li>Candidature simple</li>
                <li>Gardien validé par le propriétaire</li>
                <li>Avis après chaque garde</li>
            </ul>
        </div>

        <div class="hero-recherche">
            <div class="onglets">
                <span class="onglet actif">Trouver une garde</span>
                <a class="onglet" href="creer_annonce.php">Publier une annonce</a>
            </div>
            <form action="annonces.php" method="get">
                <label for="type_animal">Type d’animal</label>
                <select id="type_animal" name="type_animal">
                    <option value="">Tous les animaux</option>
                    <?php foreach ($typesAnimaux as $type) : ?>
                        <option value="<?= e($type) ?>"><?= e(majuscule($type)) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" placeholder="Ville ou code postal">

                <button type="submit" class="bouton bouton-plein bouton-large">Voir les annonces</button>
            </form>
        </div>
    </div>
</section>

<main class="conteneur">
    <section class="section">
        <p class="surtitre">Comment ça marche</p>
        <h2 class="titre-section">Trois étapes, zéro stress.</h2>

        <div class="grille grille-3">
            <article class="carte etape">
                <span class="numero">1</span>
                <h3>Publiez ou postulez</h3>
                <p>Le propriétaire publie une annonce avec ses dates et ses besoins. Les gardiens postulent avec un message.</p>
            </article>
            <article class="carte etape">
                <span class="numero">2</span>
                <h3>Validez un gardien</h3>
                <p>Le propriétaire choisit un candidat : l’annonce passe en « Pourvue » et les autres candidatures sont refusées.</p>
            </article>
            <article class="carte etape">
                <span class="numero">3</span>
                <h3>Notez-vous</h3>
                <p>Une fois la garde terminée, le propriétaire et le gardien se notent de 1 à 5 et laissent un commentaire.</p>
            </article>
        </div>
    </section>

    <section class="section">
        <div class="titre-ligne">
            <h2 class="titre-section">Annonces ouvertes</h2>
            <a href="annonces.php" class="bouton bouton-contour">Voir toutes les annonces</a>
        </div>

        <?php if (empty($annonces)) : ?>
            <p class="vide">Aucune annonce ouverte pour le moment.</p>
        <?php endif; ?>

        <div class="grille grille-3">
            <?php foreach ($annonces as $annonce) : ?>
                <article class="carte carte-annonce-accueil">
                    <div class="personne">
                        <span class="avatar"><?= e(initiales($annonce['prenom'], $annonce['nom'])) ?></span>
                        <div>
                            <strong><?= e(nom_complet($annonce['prenom'], $annonce['nom'])) ?></strong>
                            <small><?= e(titre($annonce['ville'])) ?></small>
                        </div>
                    </div>
                    <h3><?= e(majuscule($annonce['titre'])) ?></h3>
                    <p class="texte-doux"><?= e(majuscule($annonce['description'] ?? '')) ?></p>
                    <div class="etiquettes">
                        <span class="etiquette"><?= e(majuscule($annonce['type_animal'])) ?></span>
                        <span class="etiquette"><?= e(periode_courte($annonce['date_debut'], $annonce['date_fin'])) ?></span>
                    </div>
                    <div class="carte-pied">
                        <span class="statut statut-clair statut-ouverte">Ouverte</span>
                        <a href="annonce.php?id=<?= (int) $annonce['id_annonce'] ?>" class="bouton bouton-plein bouton-petit">Voir l’annonce</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bandeau">
        <div>
            <h2>Vous aimez les animaux ? Devenez gardien.</h2>
            <p>Parcourez les annonces ouvertes et postulez en quelques clics.</p>
        </div>
        <a href="annonces.php" class="bouton bouton-clair">Voir les annonces</a>
    </section>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
