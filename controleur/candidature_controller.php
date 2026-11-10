<?php

require_once __DIR__ . '/controleur.php';
require_once __DIR__ . '/../model/annonce.php';
require_once __DIR__ . '/../model/candidature.php';

class CandidatureController extends Controleur
{
    // écran 05 : candidatures reçues sur une de mes annonces, validation d'un gardien
    public function recues()
    {
        $idProprietaire = $this->exigerConnexion();
        $idAnnonce = $this->entier($_GET, 'id');

        $annonceModel = new Annonce();
        $annonce = $annonceModel->trouverParIdEtProprietaire($idAnnonce, $idProprietaire);

        if ($annonce === false) {
            $this->introuvable('annonce introuvable ou accès non autorisé.');
        }

        $candidatureModel = new Candidature();

        if ($this->estPost()) {
            $idCandidature = $this->entier($_POST, 'id_candidature');

            if ($annonce['statut'] === 'ouverte' && $candidatureModel->valider($idCandidature, $idAnnonce)) {
                $this->flash("Gardien validé : l'annonce est pourvue et les autres candidatures sont refusées.");
            }

            $this->rediriger('candidatures.php?id=' . $idAnnonce);
        }

        $compteurs = $candidatureModel->compterParStatut('id_annonce', $idAnnonce);

        return $this->vue([
            'annonce' => $annonce,
            'candidatures' => $candidatureModel->listerParAnnonce($idAnnonce),
            'compteurs' => $compteurs,
            'total' => array_sum($compteurs),
        ]);
    }

    // écran 09 : mes candidatures (côté gardien)
    public function mesCandidatures()
    {
        $idGardien = $this->exigerConnexion();

        $statut = $this->texte($_GET, 'statut');
        if (!in_array($statut, ['en attente', 'acceptee', 'refusee'], true)) {
            $statut = '';
        }

        $candidatureModel = new Candidature();
        $compteurs = $candidatureModel->compterParStatut('id_gardien', $idGardien);

        return $this->vue([
            'candidatures' => $candidatureModel->listerParGardien($idGardien, $statut),
            'statut' => $statut,
            'compteurs' => $compteurs,
            'total' => array_sum($compteurs),
        ]);
    }
}
