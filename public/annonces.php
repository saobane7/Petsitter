<?php

require_once __DIR__ . '/../controleur/AnnonceController.php';

$annonceController = new AnnonceController();

$annonces = $annonceController->afficherAnnoncesOuvertes();

require_once __DIR__ . '/../vue/annonces.php';
