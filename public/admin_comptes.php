<?php

require_once __DIR__ . '/../controleur/admin_controller.php';

$controleur = new AdminController();
$donnees = $controleur->comptes();

// rend disponibles dans la vue les variables retournées par le contrôleur ($annonces, $erreurs...)
extract($donnees);

require __DIR__ . '/../vue/admin_comptes.php';
