<?php
// formulaire partagé par creer_annonce.php et modifier_annonce.php
// variables attendues : $saisie, $typesAnimaux, $action_formulaire, $libelle_bouton
?>
<div class="mise-en-page mise-en-page-formulaire">
    <section class="carte carte-formulaire">
        <?php require __DIR__ . '/erreurs.php'; ?>

        <form method="post" action="<?= e($action_formulaire) ?>" class="formulaire" id="formulaire-annonce">
            <?php require __DIR__ . '/csrf.php'; ?>

            <label for="titre">Titre de l’annonce</label>
            <input type="text" id="titre" name="titre" value="<?= e($saisie['titre']) ?>" maxlength="150" placeholder="Garde de Gaston pendant les fêtes" required>

            <div class="deux-colonnes">
                <div>
                    <label for="type_animal">Type d’animal</label>
                    <select id="type_animal" name="type_animal" required>
                        <option value="">Choisir</option>
                        <?php foreach ($typesAnimaux as $type) : ?>
                            <option value="<?= e($type) ?>" <?= $saisie['type_animal'] === $type ? 'selected' : '' ?>><?= e(majuscule($type)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" value="<?= e($saisie['ville']) ?>" maxlength="100" placeholder="Évry-Courcouronnes" required>
                </div>
            </div>

            <div class="deux-colonnes">
                <div>
                    <label for="date_debut">Du</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= e($saisie['date_debut']) ?>" required>
                </div>
                <div>
                    <label for="date_fin">Au</label>
                    <input type="date" id="date_fin" name="date_fin" value="<?= e($saisie['date_fin']) ?>" required>
                </div>
            </div>

            <label for="description">Description des besoins</label>
            <textarea id="description" name="description" rows="6" placeholder="Habitudes de l’animal, consignes, accès au domicile…"><?= e($saisie['description'] ?? '') ?></textarea>

            <div class="boutons">
                <button type="submit" class="bouton bouton-plein"><?= e($libelle_bouton) ?></button>
                <a href="mes_annonces.php" class="bouton bouton-contour">Annuler</a>
            </div>
        </form>
    </section>

    <aside class="colonne-laterale">
        <section class="carte">
            <h2 class="titre-carte">Aperçu</h2>
            <article class="carte carte-annonce carte-apercu">
                <div class="carte-image statut-<?= e($saisie['statut']) ?>">
                    <span class="statut statut-badge statut-<?= e($saisie['statut']) ?>"><?= e(libelle_statut($saisie['statut'])) ?></span>
                    <?= patte(38) ?>
                </div>
                <div class="carte-corps">
                    <h3 data-apercu="titre"><?= e($saisie['titre'] !== '' ? majuscule($saisie['titre']) : 'Titre de l’annonce') ?></h3>
                    <ul class="infos">
                        <li data-apercu="type_animal"><?= e($saisie['type_animal'] !== '' ? majuscule($saisie['type_animal']) : 'Type d’animal') ?></li>
                        <li data-apercu="ville"><?= e($saisie['ville'] !== '' ? titre($saisie['ville']) : 'Ville') ?></li>
                        <li data-apercu="dates"><?= $saisie['date_debut'] !== '' && $saisie['date_fin'] !== '' ? e(periode($saisie['date_debut'], $saisie['date_fin'])) : 'Dates à renseigner' ?></li>
                    </ul>
                    <div class="carte-pied"><strong>0 candidature</strong></div>
                </div>
            </article>
        </section>

        <section class="carte">
            <h2 class="titre-carte">Cycle d’une annonce</h2>
            <ul class="cycle">
                <li class="point-ouverte"><strong>Ouverte</strong><span>Visible par les gardiens, qui peuvent postuler.</span></li>
                <li class="point-pourvue"><strong>Pourvue</strong><span>Un gardien est validé, les autres sont refusés.</span></li>
                <li class="point-terminee"><strong>Terminée</strong><span>Garde passée : les avis sont débloqués.</span></li>
            </ul>
        </section>
    </aside>
</div>
