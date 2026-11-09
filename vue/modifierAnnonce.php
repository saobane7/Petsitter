<h1>Modifier mon annonce</h1>

<?php if (!empty($erreurs)): ?>
    <?php foreach ($erreurs as $erreur): ?>
        <p><?= htmlspecialchars($erreur) ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form action="" method="POST">

    <label for="titre">Titre de l'annonce :</label>
    <input
        type="text"
        id="titre"
        name="titre"
        value="<?= htmlspecialchars($annonce['titre']) ?>"
        required
    >
    <label for="type_animal">Type d'animal :</label>
<input
    type="text"
    id="type_animal"
    name="type_animal"
    value="<?= htmlspecialchars($annonce['type_animal']) ?>"
    required
>

<label for="ville">Ville :</label>
<input
    type="text"
    id="ville"
    name="ville"
    value="<?= htmlspecialchars($annonce['ville']) ?>"
    required
>

<label for="date_debut">Date de début :</label>
<input
    type="date"
    id="date_debut"
    name="date_debut"
    value="<?= htmlspecialchars($annonce['date_debut'] ?? '') ?>"
    required
>

<label for="date_fin">Date de fin :</label>
<input
    type="date"
    id="date_fin"
    name="date_fin"
    value="<?= htmlspecialchars($annonce['date_fin'] ?? '') ?>"
    required
>

<label for="description">Description :</label>
<textarea
    id="description"
    name="description"
><?= htmlspecialchars($annonce['description'] ?? '') ?></textarea>



<button type="submit">Enregistrer les modifications</button>

</form>

