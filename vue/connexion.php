<?php
$titre_page = 'Connexion';
$page_active = 'auth';
require __DIR__ . '/partials/entete.php';
?>

<main class="conteneur page-auth">
    <section class="carte carte-auth">
        <p class="surtitre">Connexion</p>
        <h1>Bon retour !</h1>
        <p class="texte-doux">Connectez-vous pour gérer vos annonces et vos gardes.</p>

        <?php require __DIR__ . '/partials/erreurs.php'; ?>

        <form method="post" action="connexion.php" class="formulaire">
            <?php require __DIR__ . '/partials/csrf.php'; ?>

            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="vous@exemple.fr" required autocomplete="email">

            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mdp" placeholder="••••••••" required autocomplete="current-password">

            <button type="submit" class="bouton bouton-plein bouton-large">Se connecter</button>
        </form>

        <p class="auth-bas">Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
    </section>
</main>

<?php require __DIR__ . '/partials/pied.php'; ?>
