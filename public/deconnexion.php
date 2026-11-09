
<?php

// 1. Démarrer ou reprendre la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Supprimer les données de la session
$_SESSION = [];

// 3. Détruire la session
session_destroy();

// 4. Rediriger vers la connexion
header('Location: Connexion.php');
exit;


