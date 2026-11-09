<?php

require_once __DIR__ . '/../controleur/AnnonceController.php';

$annonceController = new AnnonceController();

$annonces = $annonceController->afficherMesAnnonces();

require_once __DIR__ . '/../vue/mesAnnonces.php';

?>