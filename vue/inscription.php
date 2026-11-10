<?php
$titre_page = 'Créer un compte';
$page_active = 'auth';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page-auth">
    <section class="carte carte-auth carte-auth-large">
        <p class="surtitre">Inscription</p>
        <h1>Créer un compte</h1>
        <p class="texte-doux">Rejoignez PetGarden : vous pourrez publier des annonces et garder des animaux avec le même compte.</p>

        <?php require __DIR__ . '/partials/erreurs.php'; ?>

        <form method="post" action="inscription.php" class="formulaire">
            <?php require __DIR__ . '/partials/csrf.php'; ?>

            <div class="deux-colonnes">
                <div>
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= e($saisie['prenom']) ?>" maxlength="50" required autocomplete="given-name">
                </div>
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= e($saisie['nom']) ?>" maxlength="50" required autocomplete="family-name">
                </div>
            </div>

            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="<?= e($saisie['email']) ?>" placeholder="vous@exemple.fr" maxlength="150" required autocomplete="email">

            <label for="mdp">Mot de passe (8 caractères minimum)</label>
            <input type="password" id="mdp" name="mdp" placeholder="••••••••" minlength="8" required autocomplete="new-password">

            <button type="submit" class="bouton bouton-plein bouton-large">Créer mon compte</button>
        </form>

        <p class="auth-bas">Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
    </section>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
