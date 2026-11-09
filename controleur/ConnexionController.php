    <?php

    require_once __DIR__ . '/../model/Utilisateur.php';

    class ConnexionController
    {


        public function seConnecter()
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
                }
            $erreurs = [];
            $utilisateurConnecter = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email'] ?? '');
                $mdp = $_POST['mdp'] ?? '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $erreurs[] = "l'email est invalide";
                }
                if ($mdp === '') {
                    $erreurs[] = "Le mot de passe est obligatoire";
                }
                if (empty($erreurs)) {
                    $utilisateur = new Utilisateur();
                    $resultat = $utilisateur->seConnecter($email, $mdp);

                    if ($resultat === false) {
                        $erreurs[] = "L'adresse mail ou mot de passe est incorrect";
                    } else {
                        session_regenerate_id(true);

                        $_SESSION['id_utilisateur'] = $resultat['id'];
                        $_SESSION['prenom'] = $resultat['prenom'];
                        $_SESSION['role'] = $resultat['role'];
                        $utilisateurConnecter = $resultat;
                    }
                }
            }
            return [
                'erreurs' => $erreurs,
                'utilisateur' => $utilisateurConnecter
            ];
        }
    }

    ?>