    <?php
    require_once "modele/utilisateur.class.php";

    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Petsister</title>
    </head>
    <body>
        <center>
        <h1>Petsister test </h1>

        <?php

        $utilisateur = new Utilisateur(null,"Liamidi", "Saoabne", "sao@gmail.com", "mdp", null);
        echo $utilisateur->getId();

        ?>
        </center>
    </body>
    </html>

