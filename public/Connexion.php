<?php

require_once __DIR__ . '/../controleur/ConnexionController.php';

$connexionController = new ConnexionController();
$resultat = $connexionController->seConnecter();

$erreurs = $resultat['erreurs'];
$utilisateur = $resultat['utilisateur'];

if ($utilisateur !== null) {
    header('Location: accueil.php');
    exit;
}

// Si un utilisateur est déjà connecté
if (isset($_SESSION['id_utilisateur'])) {
    header('Location: accueil.php');
    exit;
}

require_once __DIR__ . '/../vue/Connexion.php';




?>