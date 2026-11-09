
<?php

require_once __DIR__ . '/../model/Utilisateur.php';

class InscriptionController
{
    public function inscrire()
    {
        $erreurs = [];
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mdp'] ?? '';

            if ($nom === '') {
                $erreurs[] = 'Le nom est obligatoire.';
            }

            if ($prenom === '') {
                $erreurs[] = 'Le prénom est obligatoire.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "L'adresse e-mail est invalide.";
            }

            if (strlen($motDePasse) < 8) {
                $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }
            if (empty($erreurs)) {
                $utilisateur = new Utilisateur();
                
                $utilisateurExistant = $utilisateur->trouverParEmail($email);
                
                if ($utilisateurExistant) {
                    $erreurs[] = 'Cette adresse e-mail est déjà utilisée.';
                    } else {
                        $utilisateur->creer(
                            $nom,
                            $prenom,
                            $email,
                            $motDePasse
                            );
                            
                            $message = 'Votre compte a été créé avec succès.';
                            }
                        }

        }
        return [
            'erreurs' => $erreurs,
            'message' => $message
        ];
    }
}
