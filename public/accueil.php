
<?php

require_once __DIR__.'/../controleur/AccueilController.php';

$accueilController = new AccueilController();
$utilisateur = $accueilController->afficherAccueil();

require_once __DIR__.'/../vue/accueil.php';

?>