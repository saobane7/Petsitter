<?php

require_once __DIR__.'/../model/Utilisateur.php';

class AccueilController{
    
    public function afficherAccueil(){
        //Je demarre la session si elle n'est pas démarrer
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        //Je verifie que l'utilisateur est connecté

        if (!isset($_SESSION['id_utilisateur'])){
            header('location:Connexion.php');
            exit;
        }

        $id = $_SESSION['id_utilisateur'];

        $user = new Utilisateur();

        $utilisateur = $user->trouverParId($id);

        if (!$utilisateur) {
            $_SESSION = [];
            session_destroy();
            header('Location: Connexion.php');
            exit;
        }

        return $utilisateur;

    }
}

?>