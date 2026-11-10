<?php

require_once __DIR__ . '/../controleur/candidature_controller.php';

$controleur = new CandidatureController();
$donnees = $controleur->recues();

// rend disponibles dans la vue les variables retournées par le contrôleur ($annonces, $erreurs...)
extract($donnees);

require __DIR__ . '/../vue/candidatures.php';
