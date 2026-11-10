<?php

require_once __DIR__ . '/controleur.php';
require_once __DIR__ . '/../model/annonce.php';
require_once __DIR__ . '/../model/candidature.php';
require_once __DIR__ . '/../model/avis.php';

class AvisController extends Controleur
{
    // écran 06 : laisser un avis une fois la garde terminée
    // le propriétaire note le gardien retenu, le gardien retenu note le propriétaire
    public function laisser()
    {
        $idAuteur = $this->exigerConnexion();
        $idAnnonce = $this->entier($_GET, 'id');

        $annonceModel = new Annonce();
        $annonce = $annonceModel->trouverParId($idAnnonce);

        if ($annonce === false) {
            $this->introuvable('annonce introuvable.');
        }

        $candidatureModel = new Candidature();
        $gardien = $candidatureModel->gardienAccepte($idAnnonce);

        if ($gardien === false) {
            $this->introuvable("aucun gardien n'a été retenu pour cette annonce.");
        }

        if ($idAuteur === (int) $annonce['id_proprietaire']) {
            $cible = $gardien;
            $roleCible = 'Gardien';
            $retour = 'mes_annonces.php';
        } elseif ($idAuteur === (int) $gardien['id_utilisateur']) {
            $cible = [
                'id_utilisateur' => $annonce['id_proprietaire'],
                'prenom' => $annonce['prenom'],
                'nom' => $annonce['nom'],
            ];
            $roleCible = 'Propriétaire';
            $retour = 'mes_candidatures.php';
        } else {
            $this->introuvable("vous n'avez pas participé à cette garde.");
        }

        $avisModel = new Avis();
        $dejaLaisse = $avisModel->existe($idAnnonce, $idAuteur);
        $erreurs = [];
        $note = 0;
        $commentaire = '';

        if ($this->estPost()) {
            $note = $this->entier($_POST, 'note');
            $commentaire = $this->texte($_POST, 'commentaire');

            if ($annonce['statut'] !== 'terminee') {
                $erreurs[] = 'Vous pourrez laisser un avis une fois la garde terminée.';
            } elseif ($dejaLaisse) {
                $erreurs[] = 'Vous avez déjà laissé un avis pour cette garde.';
            } elseif ($note < 1 || $note > 5) {
                $erreurs[] = 'Choisissez une note de 1 à 5.';
            } elseif (mb_strlen($commentaire) > 150) {
                $erreurs[] = 'Le commentaire ne doit pas dépasser 150 caractères.';
            }

            if (empty($erreurs)) {
                $avisModel->creer($note, $commentaire === '' ? null : $commentaire, $idAnnonce, $idAuteur, $cible['id_utilisateur']);
                $this->flash('Merci, votre avis a été publié.');
                $this->rediriger($retour);
            }
        }

        return $this->vue([
            'annonce' => $annonce,
            'cible' => $cible,
            'roleCible' => $roleCible,
            'dejaLaisse' => $dejaLaisse,
            'note' => $note,
            'commentaire' => $commentaire,
            'erreurs' => $erreurs,
            'reputation' => $avisModel->reputation($idAuteur),
            'avisRecus' => $avisModel->listerRecus($idAuteur, 3),
        ]);
    }
}
