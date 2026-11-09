<?php

require_once __DIR__ . '/../controleur/AnnonceController.php';

$annonceController = new AnnonceController();

$resultat = $annonceController->modifierAnnonce();

$annonce = $resultat['annonce'];
$erreurs = $resultat['erreurs'];
$message = $resultat['message'];

require_once __DIR__ . '/../vue/modifierAnnonce.php';

?>