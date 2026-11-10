<?php

require_once __DIR__ . '/../controleur/annonce_controller.php';

$controleur = new AnnonceController();
$donnees = $controleur->annonces();

// rend disponibles dans la vue les variables retournées par le contrôleur ($annonces, $erreurs...)
extract($donnees);

require __DIR__ . '/../vue/annonces.php';
