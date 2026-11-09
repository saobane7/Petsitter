
<?php

require_once __DIR__ . '/../controleur/InscriptionController.php';

$inscriptionController = new InscriptionController();
$resultat = $inscriptionController->inscrire();


$erreurs = $resultat['erreurs'];
$message = $resultat['message'];

require __DIR__ . '/../vue/Inscription.php';
