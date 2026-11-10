<?php

require_once __DIR__ . '/controleur.php';
require_once __DIR__ . '/../model/utilisateur.php';
require_once __DIR__ . '/../model/avis.php';

class UtilisateurController extends Controleur
{
    // écran 01 : connexion
    public function connexion()
    {
        if ($this->connecte() !== null) {
            $this->rediriger('index.php');
        }

        $erreurs = [];
        $email = '';

        if ($this->estPost()) {
            $email = $this->texte($_POST, 'email');
            $mdp = $_POST['mdp'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "L'adresse e-mail est invalide.";
            }
            if (!is_string($mdp) || $mdp === '') {
                $erreurs[] = 'Le mot de passe est obligatoire.';
            }

            if (empty($erreurs)) {
                $utilisateurModel = new Utilisateur();
                $utilisateur = $utilisateurModel->seConnecter($email, $mdp);

                if ($utilisateur === false) {
                    $erreurs[] = "L'adresse e-mail ou le mot de passe est incorrect.";
                } else {
                    session_regenerate_id(true);
                    $_SESSION['id_utilisateur'] = (int) $utilisateur['id_utilisateur'];
                    $_SESSION['prenom'] = $utilisateur['prenom'];
                    $_SESSION['nom'] = $utilisateur['nom'];
                    $_SESSION['role'] = $utilisateur['role'];

                    $this->rediriger($utilisateur['role'] === 'admin' ? 'admin_moderation.php' : 'index.php');
                }
            }
        }

        return $this->vue(['erreurs' => $erreurs, 'email' => $email]);
    }

    // écran 02 : inscription (connecte directement le nouveau compte)
    public function inscription()
    {
        if ($this->connecte() !== null) {
            $this->rediriger('index.php');
        }

        $erreurs = [];
        $saisie = ['nom' => '', 'prenom' => '', 'email' => ''];

        if ($this->estPost()) {
            $saisie['nom'] = $this->texte($_POST, 'nom');
            $saisie['prenom'] = $this->texte($_POST, 'prenom');
            $saisie['email'] = $this->texte($_POST, 'email');
            $mdp = $_POST['mdp'] ?? '';

            if ($saisie['prenom'] === '' || mb_strlen($saisie['prenom']) > 50) {
                $erreurs[] = 'Le prénom est obligatoire (50 caractères maximum).';
            }
            if ($saisie['nom'] === '' || mb_strlen($saisie['nom']) > 50) {
                $erreurs[] = 'Le nom est obligatoire (50 caractères maximum).';
            }
            if (!filter_var($saisie['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($saisie['email']) > 150) {
                $erreurs[] = "L'adresse e-mail est invalide.";
            }
            if (!is_string($mdp) || mb_strlen($mdp) < 8) {
                $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }

            if (empty($erreurs)) {
                $utilisateurModel = new Utilisateur();

                if ($utilisateurModel->trouverParEmail($saisie['email'])) {
                    $erreurs[] = 'Cette adresse e-mail est déjà utilisée.';
                } else {
                    $id = $utilisateurModel->creer($saisie['nom'], $saisie['prenom'], $saisie['email'], $mdp);

                    session_regenerate_id(true);
                    $_SESSION['id_utilisateur'] = $id;
                    $_SESSION['prenom'] = $saisie['prenom'];
                    $_SESSION['nom'] = $saisie['nom'];
                    $_SESSION['role'] = 'utilisateur';

                    $this->flash('Bienvenue sur PetGarden, votre compte a été créé.');
                    $this->rediriger('index.php');
                }
            }
        }

        return $this->vue(['erreurs' => $erreurs, 'saisie' => $saisie]);
    }

    public function deconnexion()
    {
        $_SESSION = [];
        session_destroy();

        $this->rediriger('connexion.php');
    }

    // écran 10 : profil public (note moyenne, gardes réalisées, avis reçus)
    public function profil()
    {
        $id = $this->entier($_GET, 'id');

        if ($id === 0) {
            $id = $this->exigerConnexion();
        }

        $utilisateurModel = new Utilisateur();
        $profil = $utilisateurModel->trouverParId($id);

        if ($profil === false) {
            $this->introuvable('profil introuvable.');
        }

        $avisModel = new Avis();

        return $this->vue([
            'profil' => $profil,
            'statistiques' => $utilisateurModel->statistiques($id),
            'avisRecus' => $avisModel->listerRecus($id),
        ]);
    }
}
