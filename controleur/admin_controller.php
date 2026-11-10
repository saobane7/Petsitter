<?php

require_once __DIR__ . '/controleur.php';
require_once __DIR__ . '/../model/utilisateur.php';
require_once __DIR__ . '/../model/annonce.php';
require_once __DIR__ . '/../model/avis.php';

class AdminController extends Controleur
{
    // écran 11 : modération des avis et statistiques globales
    public function moderation()
    {
        $this->exigerAdmin();
        $avisModel = new Avis();

        if ($this->estPost()) {
            $avisModel->supprimer($this->entier($_POST, 'id_avis'));
            $this->flash("L'avis a été supprimé.");
            $this->rediriger('admin_moderation.php');
        }

        $annonceModel = new Annonce();
        $utilisateurModel = new Utilisateur();

        return $this->vue([
            'gardesRealisees' => $annonceModel->compterTerminees(),
            'statistiquesAvis' => $avisModel->statistiques(),
            'nbComptes' => $utilisateurModel->compter(),
            'avisRecents' => $avisModel->listerRecents(),
        ]);
    }

    // écran 12 : comptes utilisateurs (changement de rôle, suppression)
    public function comptes()
    {
        $idAdmin = $this->exigerAdmin();
        $utilisateurModel = new Utilisateur();

        if ($this->estPost()) {
            $idCompte = $this->entier($_POST, 'id_utilisateur');
            $action = $this->texte($_POST, 'action');

            // un administrateur ne modifie pas son propre compte depuis cet écran
            if ($idCompte !== 0 && $idCompte !== $idAdmin) {
                if ($action === 'role') {
                    $role = $this->texte($_POST, 'role');
                    if (in_array($role, ['utilisateur', 'admin'], true)) {
                        $utilisateurModel->changerRole($idCompte, $role);
                        $this->flash('Le rôle du compte a été modifié.');
                    }
                } elseif ($action === 'supprimer') {
                    $utilisateurModel->supprimer($idCompte);
                    $this->flash('Le compte et ses données ont été supprimés.');
                }
            }

            $this->rediriger('admin_comptes.php');
        }

        return $this->vue([
            'comptes' => $utilisateurModel->listerTous(),
            'idAdmin' => $idAdmin,
        ]);
    }
}
