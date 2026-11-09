<?php

require_once __DIR__ .'/../controleur/AnnonceController.php';

$annonceController = new AnnonceController();

$resultat = $annonceController->creerAnnonce();

$erreurs = $resultat['erreurs'];
$message = $resultat['message'];

require_once __DIR__ . '/../vue/creerAnnonce.php';





?>