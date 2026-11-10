<?php

require_once __DIR__ . '/controleur.php';
require_once __DIR__ . '/../model/annonce.php';
require_once __DIR__ . '/../model/candidature.php';
require_once __DIR__ . '/../model/avis.php';

class AnnonceController extends Controleur
{
    // écran 00 : accueil (recherche + dernières annonces ouvertes)
    public function accueil()
    {
        $annonceModel = new Annonce();

        return $this->vue([
            'annonces' => $annonceModel->listerOuvertes('', '', 3),
            'typesAnimaux' => $this->typesAnimaux,
        ]);
    }

    // écran 07 : annonces ouvertes avec filtres
    public function annonces()
    {
        $filtres = [
            'type_animal' => $this->texte($_GET, 'type_animal'),
            'ville' => $this->texte($_GET, 'ville'),
        ];

        $annonceModel = new Annonce();

        return $this->vue([
            'annonces' => $annonceModel->listerOuvertes($filtres['type_animal'], $filtres['ville']),
            'filtres' => $filtres,
            'typesAnimaux' => $this->typesAnimaux,
        ]);
    }

    // écran 08 : détail d'une annonce et formulaire de candidature
    public function detail()
    {
        $idAnnonce = $this->entier($_GET, 'id');

        $annonceModel = new Annonce();
        $annonce = $annonceModel->trouverParId($idAnnonce);

        if ($annonce === false) {
            $this->introuvable('annonce introuvable.');
        }

        $connecte = $this->connecte();
        $candidatureModel = new Candidature();
        $erreurs = [];
        $message = '';

        $estProprietaire = $connecte !== null && $connecte['id'] === (int) $annonce['id_proprietaire'];
        $maCandidature = $connecte !== null
            ? $candidatureModel->trouverParAnnonceEtGardien($idAnnonce, $connecte['id'])
            : false;

        if ($this->estPost()) {
            $idGardien = $this->exigerConnexion();
            $message = $this->texte($_POST, 'message');

            if ($estProprietaire) {
                $erreurs[] = 'Vous ne pouvez pas postuler à votre propre annonce.';
            } elseif ($annonce['statut'] !== 'ouverte') {
                $erreurs[] = "Cette annonce n'accepte plus de candidatures.";
            } elseif ($maCandidature !== false) {
                $erreurs[] = 'Vous avez déjà postulé à cette annonce.';
            } elseif ($message === '') {
                $erreurs[] = 'Le message de motivation est obligatoire.';
            } elseif (mb_strlen($message) > 2000) {
                $erreurs[] = 'Le message ne doit pas dépasser 2000 caractères.';
            }

            if (empty($erreurs)) {
                $candidatureModel->creer($message, $idAnnonce, $idGardien);
                $this->flash('Votre candidature a été envoyée au propriétaire.');
                $this->rediriger('mes_candidatures.php');
            }
        }

        $avisModel = new Avis();

        return $this->vue([
            'annonce' => $annonce,
            'reputation' => $avisModel->reputation($annonce['id_proprietaire']),
            'estProprietaire' => $estProprietaire,
            'maCandidature' => $maCandidature,
            'erreurs' => $erreurs,
            'message' => $message,
        ]);
    }

    // écran 03 : mes annonces (statistiques, filtres par statut, clôture d'une garde)
    public function mesAnnonces()
    {
        $idProprietaire = $this->exigerConnexion();
        $annonceModel = new Annonce();

        if ($this->estPost()) {
            $idAnnonce = $this->entier($_POST, 'id_annonce');

            if ($annonceModel->changerStatut($idAnnonce, $idProprietaire, 'pourvue', 'terminee')) {
                $this->flash('La garde est terminée : vous pouvez maintenant laisser un avis.');
            }

            $this->rediriger('mes_annonces.php');
        }

        $statut = $this->texte($_GET, 'statut');
        if (!in_array($statut, ['ouverte', 'pourvue', 'terminee'], true)) {
            $statut = '';
        }

        $compteurs = $annonceModel->compterParStatut($idProprietaire);

        return $this->vue([
            'annonces' => $annonceModel->trouverParProprietaire($idProprietaire, $statut),
            'statut' => $statut,
            'compteurs' => $compteurs,
            'total' => array_sum($compteurs),
            'gardesConfiees' => $compteurs['pourvue'] + $compteurs['terminee'],
            'gardienFavori' => $annonceModel->gardienLePlusSollicite($idProprietaire),
        ]);
    }

    // écran 04 : publier une annonce
    public function creer()
    {
        $idProprietaire = $this->exigerConnexion();
        $saisie = $this->saisieVide();
        $erreurs = [];

        if ($this->estPost()) {
            $saisie = $this->lireSaisie();
            $erreurs = $this->valider($saisie);

            if (empty($erreurs)) {
                $annonceModel = new Annonce();
                $annonceModel->creer(
                    $saisie['titre'],
                    $saisie['type_animal'],
                    $saisie['ville'],
                    $saisie['date_debut'],
                    $saisie['date_fin'],
                    $saisie['description'],
                    $idProprietaire
                );

                $this->flash('Votre annonce a été publiée.');
                $this->rediriger('mes_annonces.php');
            }
        }

        return $this->vue([
            'saisie' => $saisie,
            'erreurs' => $erreurs,
            'typesAnimaux' => $this->typesAnimaux,
        ]);
    }

    // modifier une annonce (même écran que la publication)
    public function modifier()
    {
        $idProprietaire = $this->exigerConnexion();
        $idAnnonce = $this->entier($_GET, 'id');

        $annonceModel = new Annonce();
        $annonce = $annonceModel->trouverParIdEtProprietaire($idAnnonce, $idProprietaire);

        if ($annonce === false) {
            $this->introuvable('annonce introuvable ou accès non autorisé.');
        }

        // une annonce pourvue ou terminée n'est plus modifiable
        if ($annonce['statut'] !== 'ouverte') {
            $this->flash('Seule une annonce ouverte peut être modifiée.');
            $this->rediriger('mes_annonces.php');
        }

        $saisie = $annonce;
        $erreurs = [];

        if ($this->estPost()) {
            $saisie = $this->lireSaisie();
            $erreurs = $this->valider($saisie);

            if (empty($erreurs)) {
                $annonceModel->modifier(
                    $idAnnonce,
                    $saisie['titre'],
                    $saisie['type_animal'],
                    $saisie['ville'],
                    $saisie['date_debut'],
                    $saisie['date_fin'],
                    $saisie['description'],
                    $idProprietaire
                );

                $this->flash('Votre annonce a été modifiée.');
                $this->rediriger('mes_annonces.php');
            }
        }

        $saisie['statut'] = $annonce['statut'];

        return $this->vue([
            'annonce' => $annonce,
            'saisie' => $saisie,
            'erreurs' => $erreurs,
            'typesAnimaux' => $this->typesAnimaux,
        ]);
    }

    private function saisieVide()
    {
        return [
            'titre' => '',
            'type_animal' => '',
            'ville' => '',
            'date_debut' => '',
            'date_fin' => '',
            'description' => '',
            'statut' => 'ouverte',
        ];
    }

    private function lireSaisie()
    {
        return [
            'titre' => $this->texte($_POST, 'titre'),
            'type_animal' => $this->texte($_POST, 'type_animal'),
            'ville' => $this->texte($_POST, 'ville'),
            'date_debut' => $this->texte($_POST, 'date_debut'),
            'date_fin' => $this->texte($_POST, 'date_fin'),
            'description' => $this->texte($_POST, 'description'),
            'statut' => 'ouverte',
        ];
    }

    private function valider($saisie)
    {
        $erreurs = [];

        if ($saisie['titre'] === '' || mb_strlen($saisie['titre']) > 150) {
            $erreurs[] = 'Le titre est obligatoire (150 caractères maximum).';
        }
        if (!in_array($saisie['type_animal'], $this->typesAnimaux, true)) {
            $erreurs[] = "Choisissez un type d'animal.";
        }
        if ($saisie['ville'] === '' || mb_strlen($saisie['ville']) > 100) {
            $erreurs[] = 'La ville est obligatoire (100 caractères maximum).';
        }

        $debut = DateTime::createFromFormat('!Y-m-d', $saisie['date_debut']);
        $fin = DateTime::createFromFormat('!Y-m-d', $saisie['date_fin']);

        if ($debut === false || $fin === false) {
            $erreurs[] = 'Les dates sont obligatoires.';
        } elseif ($fin < $debut) {
            $erreurs[] = 'La date de fin doit être postérieure ou égale à la date de début.';
        }

        return $erreurs;
    }
}
