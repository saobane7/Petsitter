<?php

require_once __DIR__ . '/../controleur/candidature_controller.php';

$controleur = new CandidatureController();
$donnees = $controleur->mesCandidatures();

// rend disponibles dans la vue les variables retournées par le contrôleur ($annonces, $erreurs...)
extract($donnees);

require __DIR__ . '/../vue/mes_candidatures.php';
