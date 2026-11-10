<?php

require_once __DIR__ . '/../controleur/utilisateur_controller.php';

$controleur = new UtilisateurController();
$donnees = $controleur->inscription();

// rend disponibles dans la vue les variables retournées par le contrôleur ($annonces, $erreurs...)
extract($donnees);

require __DIR__ . '/../vue/inscription.php';
