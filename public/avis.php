<?php

require_once __DIR__ . '/../controleur/avis_controller.php';

$controleur = new AvisController();
$donnees = $controleur->laisser();

// rend disponibles dans la vue les variables retournées par le contrôleur ($annonces, $erreurs...)
extract($donnees);

require __DIR__ . '/../vue/avis.php';
