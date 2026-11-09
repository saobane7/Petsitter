
<?php

require_once __DIR__ . '/../model/Annonce.php';

class AnnonceController
{
    public function afficherAnnoncesOuvertes()
    {
        $annonce = new Annonce();

        return $annonce->listerOuvertes();
    }

    public function creerAnnonce()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: Connexion.php');
            exit;
        }

        // Initialisation
        $erreurs = [];
        $message = '';

        $titre = '';
        $typeAnimal = '';
        $ville = '';
        $dateDebut = '';
        $dateFin = '';
        $description = '';

        $idProprietaire = $_SESSION['id_utilisateur'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupération des données
            $titre = trim($_POST['titre'] ?? '');
            $typeAnimal = trim($_POST['type_animal'] ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $dateDebut = $_POST['date_debut'] ?? '';
            $dateFin = $_POST['date_fin'] ?? '';
            $description = trim($_POST['description'] ?? '');

            // Validation du titre
            if (empty($titre)) {
                $erreurs[] = "Le titre est obligatoire.";
            }

            // Validation du type d'animal
            if (empty($typeAnimal)) {
                $erreurs[] = "Le type d'animal est obligatoire.";
            }

            // Validation de la ville
            if (empty($ville)) {
                $erreurs[] = "La ville est obligatoire.";
            }

            // Validation des dates
            if (empty($dateDebut) || empty($dateFin)) {
                $erreurs[] = "Les dates sont obligatoires.";
            } elseif ($dateFin < $dateDebut) {
                $erreurs[] = "La date de fin doit être postérieure à la date de début.";
            }

            // Enregistrement
            if (empty($erreurs)) {

                $annonce = new Annonce();

                $annonce->creer(
                    $titre,
                    $typeAnimal,
                    $ville,
                    $dateDebut,
                    $dateFin,
                    $description,
                    $idProprietaire
                );

                $message = "Votre annonce a été publiée avec succès.";
            }
        }

        return [
            'erreurs' => $erreurs,
            'message' => $message
        ];
    }

        public function afficherMesAnnonces()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: Connexion.php');
            exit;
        }

        $idProprietaire = $_SESSION['id_utilisateur'];

        $annonce = new Annonce();

        $annonces = $annonce->trouverParProprietaire($idProprietaire);

        return $annonces;
    }

        public function modifierAnnonce()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: Connexion.php');
            exit;
        }

        $idAnnonce = $_GET['id'] ?? null;
        if (!filter_var($idAnnonce, FILTER_VALIDATE_INT)) {
            die("Identifiant d'annonce invalide.");
            }
        $idProprietaire = $_SESSION['id_utilisateur'];

        $annonceModel = new Annonce();

        $annonce = $annonceModel->trouverParIdEtProprietaire(
                $idAnnonce,
                $idProprietaire
            );


            if ($annonce === false) {
                die("Annonce introuvable ou accès non autorisé.");
        }

        $erreurs = [];
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $typeAnimal = trim($_POST['type_animal'] ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $dateDebut = $_POST['date_debut'] ?? '';
            $dateFin = $_POST['date_fin'] ?? '';
            $description = trim($_POST['description'] ?? '');
        

        if (empty($titre)) {
            $erreurs[] = "Le titre est obligatoire.";
        }
        if (empty($typeAnimal)){
            $erreurs[] = "Le type d'animal est obligatoire.";
        }
        if (empty($ville)){
            $erreurs[] = "La ville est obligatoire.";
        }
        if (empty($dateDebut) || empty($dateFin)) {
            $erreurs[] = "Les dates sont obligatoires.";
        } elseif ($dateFin < $dateDebut) {
            $erreurs[] = "La date de fin doit être postérieure ou égale à la date de début.";
        }
        if (empty($erreurs)) {
            $annonceModel->modifier(
            $idAnnonce,
            $titre,
            $typeAnimal,
            $ville,
            $dateDebut,
            $dateFin,
            $description,
            $idProprietaire
            );
            $message = "Votre annonce a été modifiée avec succès.";
            $annonce = $annonceModel->trouverParIdEtProprietaire(
                $idAnnonce,
                $idProprietaire
            );
            
            }
        }
        return [
            'annonce' => $annonce,
            'erreurs' => $erreurs,
            'message' => $message
            ];
    }

}